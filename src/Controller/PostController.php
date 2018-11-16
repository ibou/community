<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/posts")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1"}, methods={"GET"}, name="index")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods={"GET"}, name="blog_index_paginated")
     */
    public function index(Request $request, int $page, PostRepository $posts, TagRepository $tags): Response
    {
        $tag = null;
        if($request->query->has('tag')){
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }
        $lastest = $posts->findLatest($page, $tag); 
        return $this->render('post/index.html.twig', [
            'posts' => $lastest
        ]);
    }

    /**
     * @Route("/posts/{slug}", methods={"GET"}, name="blog_post")
     *
     * NOTE: The $post controller argument is automatically injected by Symfony
     * after performing a database query looking for a Post with the 'slug'
     * value given in the route.
     * See https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html
     */
    public function postShow(Post $post): Response
    {

         return $this->render('post/post_show.html.twig', [
            'post' => $post
        ]);
    }
}
