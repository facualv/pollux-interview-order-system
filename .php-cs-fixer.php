<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)->exclude([
        'storage',
        'vendor',
        'resources',
        'bootstrap/cache',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);