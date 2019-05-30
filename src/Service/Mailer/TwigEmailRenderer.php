<?php
namespace App\Service\Mailer;

use App\Service\Mailer\RenderedEmail;

class TwigEmailRenderer implements EmailRendererInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    public function render(string $template, array $data): RenderedEmail
    {
        $data = $this->twig->mergeGlobals($data);
        $template = $this->twig->loadTemplate($template);
        $subject = $template->renderBlock('subject', $data);
        $body = $template->renderBlock('body', $data);
        return new RenderedEmail($subject, $body);
    }
}
