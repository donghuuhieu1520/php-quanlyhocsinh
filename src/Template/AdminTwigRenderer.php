<?php declare(strict_types = 1);

namespace App\Template;

use Twig\Environment;

class AdminTwigRenderer implements IAdminRenderer
{
    private $renderer;

    public function __construct(Environment $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render($template, $data = []) : string
    {
        $data = array_merge($data, [
            'menuItems' => [['href' => '/', 'text' => 'Homepage']],
            'manage_classes' => array_map(function ($class) {
                return [
                    'id' => $class['id'],
                    'name' => $class['name']
                ];
            }, $_SESSION['manage_classes']),
            'account' => [
                'id' => $_SESSION['account_login']['id'],
                'name' => $_SESSION['account_login']['name'],
            ]
        ]);
        return $this->renderer->render("$template.html", $data);
    }
}
