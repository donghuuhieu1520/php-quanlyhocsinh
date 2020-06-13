<?php declare(strict_types = 1);

namespace App\Template;

class AdminTwigRenderer implements IAdminRenderer
{
    private $renderer;

    public function __construct(IAdminRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render($template, $data = []) : string
    {
        $data = array_merge($data, [
            'menuItems' => [['href' => '/', 'text' => 'Homepage']],
        ]);
        return $this->renderer->render($template, $data);
    }
}
