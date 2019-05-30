<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Ma class event
 */
class UserEvent extends EventDispatcher
{

    private $params;
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $user;

    const EMAIL_RESET_PASSWORD = 'user.reset.password';

    public function __construct(User $user, array $params = [])
    {
        $this->user = $user;
        $this->params = $params;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getParams()
    {
        return $this->params;
    }
}
