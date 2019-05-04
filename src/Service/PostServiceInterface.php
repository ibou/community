<?php
namespace App\Service;

use App\Entity\Post;
use App\Entity\User;

interface PostServiceInterface
{

    /**
     * Undocumented function
     *
     * @param Post $post
     * @return void
     */
    public function create(Post $post): void;
    /**
     * Undocumented function
     *
     * @param integer $id
     * @return Post|null
     */
    public function getPostById(int $id): ?Post;
    /**
     * Undocumented function
     *
     * @param User $user
     * @return array
     */
    public function getPostsByUser(User $user): array;
}
