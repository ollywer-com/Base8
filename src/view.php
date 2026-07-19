<?php

declare(strict_types=1);

/**
 * Renders a view.
 *
 * The view is loaded from the application's `views/` directory.
 * Variables supplied in `$data` are extracted into the local symbol table.
 *
 * @param string $view
 *     View name relative to the `views/` directory.
 *     Do not include the `.php` extension.
 *
 * @param array $data
 *     Variables available inside the view.
 *
 * @return never
 */
function b8_view(string $view, array $data = []): never
{
    if (
        $view === '' ||
        str_contains($view, '..') ||
        str_contains($view, '\\') ||
        str_contains($view, "\0")
    ) {
        b8_status(404);
        exit();
    }

    $file = b8_root() . "/views/{$view}.php";

    if (!is_file($file)) {
        b8_status(404);
        exit();
    }

    extract($data, EXTR_SKIP);

    require $file;

    exit();
}