<?php
namespace App\Repository;

use App\Entity\Post;

interface PostRepositoryInterface{

    /**
     * Undocumented function
     *
     * @param Post $post
     * @return void
     */
    public function save(Post $post): void;

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return Post|null
     */
    public function findById(int $id): ?Post;
}