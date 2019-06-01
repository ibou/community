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
    /**
     * @var array
     */
    private $params;

    /**
     * PostEvent constructor.
     *
     * @param Post $post
     * @param array $params
     */
    public function __construct(Post $post, array $params = [])
    {
        $this->post = $post;
        $this->params = $params;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getParams()
    {
        return $this->params;
    }
}
