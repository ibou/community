<?php

namespace App\Controller;

use App\Elasticsearch\Search;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Form\CommentType;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Elastica\Client;
use Elastica\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;

/**
 * @Route("/posts")
 */
class PostController extends AbstractController
{
    public function listTags(Request $request, TagRepository $tags): Response
    {
        $all = $tags->findAll();

        return $this->render('post/side-tags.html.twig', [
            'tags' => $all,
        ]);
    }

    /**
     * @Route("", defaults={"page": "1","query": ""}, methods={"GET"}, name="post_index")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"page": "1","query": ""}, methods={"GET"}, name="post_index_paginated_search")
     */
    public function index(Request $request, int $page, $query, PostRepository $posts, TagRepository $tags, Client $client): Response
    {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }
        $currentRoute = $request->attributes->get('_route');
        $page = null !== $request->attributes->get('page') ? $request->attributes->get('page') : 1;
        $query = $request->query->get('query', '');
        if ($request->query->has('query')) {
            $limit = $request->query->get('limit', 15);
            $tags = $request->query->get('tags', false);
            if (false !== $tags) {
            }
            $search = new Search($client, $query, $tags);
            $search->setLimit(500);
            $search->setPage($page);
            $result = $search->runSearch();
            $source = $result['source'];
            $tags = $result['aggr'];
            $lastest = $search->getPaginatedData($source);
        } else {
            $lastest = $posts->findLatest($page, $tag);
        }
        //Inclure template dans index selon si search or direct access (a gerer dans le template index.html.twig)

        return $this->render('post/list.html.twig', [
            'posts' => $lastest,
            'tags' => $tags,
            'query' => $query,
        ]);
    }

    /**
     * @Route("/article/{slug}", methods={"GET"}, name="post_show")
     *
     * NOTE: The $post controller argument is automatically injected by Symfony
     * after performing a database query looking for a Post with the 'slug'
     * value given in the route.
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function postShow(Post $post): Response
    {
        return $this->render('post/post_show.html.twig', [
            'post' => $post,
            'form' => $this->_newFormComment(),
        ]);
    }

    private function _newFormComment()
    {
        $form = $this->createForm(CommentType::class);

        return $form->createView();
    }

    /**
     * This controller is called directly via the render() function in the
     * blog/post_show.html.twig template. That's why it's not needed to define
     * a route name for it.
     *
     * The "id" of the Post is passed in and then turned into a Post object
     * automatically by the ParamConverter.
     */
    public function commentForm(Request $request, Post $post, Comment $comment = null): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('post/_comment_form.html.twig', [
            'post' => $post,
            'comment' => $comment,
            'parent_id' => $request->attributes->get('p_id'),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{postSlug}/new", methods={"POST"}, name="comment_new")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     *
     * NOTE: The ParamConverter mapping is required because the route parameter
     * (postSlug) doesn't match any of the Doctrine entity properties (slug).
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter
     */
    public function commentNew(Request $request, Post $post, EventDispatcherInterface $eventDispatcher): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $parent_id = $request->request->get('parent');
            $parent = null;
            $comment->setAuthor($this->getUser());
            $post->addComment($comment);
            foreach ($post->getComments() as $parentComment) {
                if ((bool) $parent_id && $parent_id == $parentComment->getId()) {
                    $parent = $parentComment;
                    $comment->setParent($parent);
                    break;
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            // $em->flush();

            return $this->json([
                'slug' => $post->getSlug(), ], 201);

            // return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        // return $this->render('blog/comment_form_error.html.twig', [
        //     'post' => $post,
        //     'form' => $form->createView(),
        // ]);
    }

    /**
     * @Route("/search", name="post_search")
     */
    public function search(Request $request, Client $client): Response
    {
        return $this->render('post/search.html.twig',
            ['results' => []]
        );
    }

    /**
     * @Route("/{id}/like", name="post_like")
     *
     * @param Post               $post
     * @param ObjectManager      $manager
     * @param PostLikeRepository $likeRepos
     *
     * @return Response
     */
    public function like(Post $post, ObjectManager $manager, PostLikeRepository $likeRepos): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['code' => 200, 'message' => 'UnAuthorized'], 403);
        }
        if ($post->isLikedByUser($user)) {
            $like = $likeRepos->findOneBy([
                'user' => $user,
                'post' => $post,
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' => $likeRepos->count([
                    'post' => $post,
                ]),
            ], 200);
        }
        $like = new PostLike();
        $like->setPost($post)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like ajouté avec succès',
            'likes' => $likeRepos->count([
                'post' => $post,
            ]),
        ], 200);
    }
}
