<?php
namespace App\Event;

use App\Entity\Post;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class PostEvent extends EventDispatcher
{

    public const CREATED = 'post.created';

    /**
     * Undocumented variable
     *
     * @var Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
