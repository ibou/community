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
                'author' => "{$post->getAuthor()->getFirstname()} - {$post->getAuthor()->getLastname()}",
                'content' => $post->getContent(),
                'comments' => $comments,

                // Not indexed but needed for display
                'url' => $this->router->generate('post_show', ['slug' => $post->getSlug()], UrlGeneratorInterface::ABSOLUTE_PATH),
                'url_post' => $this->router->generate('post_index', [], UrlGeneratorInterface::ABSOLUTE_PATH),
                'publishedAt' => $post->getPublishedAt()->format('d M Y à H:i:s'),
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

        //$this->logger->debug('ELASTIC SEARCH : DATA REINDEXED', $documents);
        $index->addDocuments($documents);
        $index->refresh();
    }
}
