<?php

namespace App\Service\Mailer;

use Twig\Environment;

class TwigEmailRenderer implements EmailRendererInterface
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(string $template, array $data): RenderedEmail
    {
        $data = $this->twig->mergeGlobals($data);
        $template = $this->twig->load($template);
        $subject = $template->renderBlock('subject', $data);
        $body = $template->renderBlock('body', $data);
        return new RenderedEmail($subject, $body);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function renderTempalte(string $template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }
}
