<?php
namespace App\Service;

use App\Entity\Post;

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
}
