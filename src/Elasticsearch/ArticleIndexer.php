<?php

namespace App\Elasticsearch;

use App\Entity\Post;
use App\Repository\PostRepository;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleIndexer
{
    private $client;
    private $postRepository;
    private $router;

    public function __construct(Client $client, PostRepository $postRepository, UrlGeneratorInterface $router)
    {
        $this->client = $client;
        $this->postRepository = $postRepository;
        $this->router = $router;
    }

    public function buildDocument(Post $post)
    {
        $tags = [];
        $tag_string = '<p class="post-tags">';
        foreach ($post->getTags() as $tag) {
            $tags[] = $tag->getName();
            $tag_string .= '<i class="fa fa-tag"></i>'.$tag->getName();
        }
        $tag_string .= '</p>';
        $comments = [];
        foreach ($post->getComments() as $comment) {
            $comments[] = $comment->getContent();
        }

        return new Document(
            $post->getId(), // Manually defined ID
            [
                'title' => $post->getTitle(),
                'tags' => $tags,
                'tag' => $tag_string,
                'author' => "{$post->getAuthor()->getFirstname()} - {$post->getAuthor()->getLastname()}",
                'content' => $post->getContent(),
                'comments' => $comments,

                // Not indexed but needed for display
                'url' => $this->router->generate('post_show', ['slug' => $post->getSlug()], UrlGeneratorInterface::ABSOLUTE_PATH),
                'date' => $post->getPublishedAt()->format('M d, Y'),
            ],
            'article' // Types are deprecated, to be removed in Elastic 7
        );
    }

    public function indexAllDocuments($indexName)
    {
        $allPosts = $this->postRepository->findAll();
        $index = $this->client->getIndex($indexName);

        $documents = [];
        foreach ($allPosts as $post) {
            $documents[] = $this->buildDocument($post);
        }

        $index->addDocuments($documents);
        $index->refresh();
    }
}
