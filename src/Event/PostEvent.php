<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Post;

final class PostEvent extends Event
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
