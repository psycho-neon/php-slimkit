<?php

declare(strict_types=1);

namespace App\Support;

final class ValidateForms
{
    public static function convertErrors(array $errors): array
    {
        $container = [];
        foreach ($errors as $name => $tree) {
            foreach ($tree as $err) {
                $container[$name] = $err;
            }
        }

        return $container;
    }
}
