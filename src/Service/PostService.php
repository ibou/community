<?php
namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepositoryInterface;
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
     * Constructor of PostService
     *
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(PostRepositoryInterface $postRepository){
        $this->postRepository = $postRepository;
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
