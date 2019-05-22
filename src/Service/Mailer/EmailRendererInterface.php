<?php
namespace App\Service\Mailer;

use App\Service\Mailer\RenderedEmail;


interface EmailRendererInterface
{
    public function render(string $template, array $data): RenderedEmail;
}
