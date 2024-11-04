<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!file_exists(__DIR__.'/src')) {
    exit(0);
}

$fileHeaderComment = <<<'EOF'
This file is part of the Trexima/CV project.

(c) TREXIMA Bratislava, spol. s r.o. <itmgmt@trexima.sk>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;


return (new PhpCsFixer\Config())
    ->setRules([
        '@PHP83Migration' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'protected_to_private' => false,
        'native_constant_invocation' => ['strict' => false],
        // 'header_comment' => ['header' => $fileHeaderComment],
        'modernize_strpos' => true,
        'get_class_to_class_keyword' => true,
        // 'phpdoc_to_return_type' => true,
        'void_return' => true,

    ])
    ->setRiskyAllowed(true)
    ->setCacheFile('.php-cs-fixer.cache');