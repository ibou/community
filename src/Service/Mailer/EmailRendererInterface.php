<?php
namespace App\Service\Mailer;

interface EmailRendererInterface
{
    public function render(string $template, array $data): RenderedEmail;
    public function renderTempalte(string $template): string;
}
