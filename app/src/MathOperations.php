<?php

namespace App;

class MathOperations
{
    public function __call($method, $args)
    {
        // Flatten the arguments if they are wrapped in an array
        $args = is_array($args[0]) ? $args[0] : $args;

        switch ($method) {
            case 'add':
                return array_reduce($args, function ($carry, $item) {
                    return $carry + $item;
                }, 0);
            case 'divide':
                return array_reduce($args, function ($carry, $item) {
                    return $carry / $item;
                }, array_shift($args)); // Start with the first value
            case 'multiply':
                return array_reduce($args, function ($carry, $item) {
                    return $carry * $item;
                }, 1);
            case 'substract':
                return array_reduce($args, function ($carry, $item) {
                    return $carry - $item;
                }, array_shift($args)); // Start with the first value
            default:
                throw new \InvalidArgumentException("Unknown operation: {$method}");
        }
    }
}
