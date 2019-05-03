<?php
namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepositoryInterface;
use App\Event\PostEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Service
 * @package App\Service
 */
class PostService implements PostServiceInterface
{

    /**
     * Undocumented variable
     *
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * Undocumented variable
     *
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * Constructor of PostService
     *
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository, EventDispatcherInterface $eventDispatcher){
        $this->postRepository = $postRepository;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * Create a new post
     *
     * @param Post $post
     * @return void
     */
    public function create(Post $post): void
    {
        $this->postRepository->save($post);
        $this->eventDispatcher->dispatch(PostEvent::CREATED, new PostEvent($post));
    }

    /**
     * Undocumented function
     *
     * @param integer $idPost
     * @return Post|null
     */
    public function getPostById(int $idPost): ?Post
    {
        return $this->postRepository->findById($idPost);
    }

}
