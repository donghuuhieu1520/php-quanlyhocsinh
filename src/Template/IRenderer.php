<?php declare(strict_types = 1);

namespace App\Template;

interface IRenderer
{
    public function render($template, $data = []) : string;
}
