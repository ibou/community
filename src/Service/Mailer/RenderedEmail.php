<?php
namespace App\Service\Mailer;

class RenderedEmail
{
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $body;
    public function __construct(string $subject, string $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }
    public function subject(): string
    {
        return $this->subject;
    }
    public function body(): string
    {
        return $this->body;
    }
}
