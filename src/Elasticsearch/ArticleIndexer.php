<?php

namespace App\Elasticsearch;

use App\Entity\Post;
use App\Repository\PostRepository;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

class ArticleIndexer
{
    private $client;
    private $postRepository;
    private $router;
    private $logger;

    public function __construct(Client $client, PostRepository $postRepository, UrlGeneratorInterface $router, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->postRepository = $postRepository;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function buildDocument(Post $post)
    {
        $tags = [];
        foreach ($post->getTags() as $tag) {
            $tags[] = $tag->getName();
        }
        $comments = [];
        foreach ($post->getComments() as $comment) {
            $comments[] = $comment->getContent();
        }

        return new Document(
            $post->getId(), // Manually defined ID
            [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'tags' => $tags,
                'fullAuthorName' => $post->fullAuthorName(),
                'content' => $post->getContent(),
                'comments' => $comments,
                'slug' => $post->getSlug(),
                'numberLikes' => $post->numberLikes(),
                'likes' => $post->getLikes(),

                // Not indexed but needed for display
                'url' => $this->router->generate('post_show', ['slug' => $post->getSlug()], UrlGeneratorInterface::ABSOLUTE_PATH),
                'url_post' => $this->router->generate('post_index', [], UrlGeneratorInterface::ABSOLUTE_PATH),
                'publishedAt' => $post->getPublishedAt()->format('c'),
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
