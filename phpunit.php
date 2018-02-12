#!/usr/bin/env php
<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if (version_compare('7.1.2', PHP_VERSION, '>')) {
    fwrite(
        STDERR,
        sprintf(
            'This version of PHPUnit is supported on PHP 7.1.2' . PHP_EOL .
            'You are using PHP %s (%s).' . PHP_EOL,
            PHP_VERSION,
            PHP_BINARY
        )
    );
    die(1);
}
if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}
foreach (array(__DIR__ . '/../../autoload-classmap.php', __DIR__ . '/../../autoload_files.php', __DIR__ . '/../../autoload_namespaces.php', __DIR__ . '/../../autoload_psr4.php', __DIR__ . '/../vendor/autoload_real.php', __DIR__ . '/vendor/autoload_static.php') as $file) {
    if (file_exists($file)) {
        define('PHPUNIT_COMPOSER_INSTALL', $file);
        break;
    }
}
unset($file);
if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
    fwrite(
        STDERR,
        'You need to set up the project dependencies using Composer:' . PHP_EOL . PHP_EOL .
        '    composer install' . PHP_EOL . PHP_EOL .
        'You can learn all about Composer on https://getcomposer.org/.' . PHP_EOL
    );
    die(1);
}
require PHPUNIT_COMPOSER_INSTALL;
PHPUnit\TextUI\Command::main();
