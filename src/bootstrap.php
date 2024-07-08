<?php
define('APP_ROOT', dirname(__DIR__, 1) );

require APP_ROOT . '/config/config.php';
require APP_ROOT . '/public/includes/functions.php';
require APP_ROOT . '/vendor/autoload.php';


use EdvGraz\CMS\Cms;
use EdvGraz\CMS\Testklasse;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;


$loader = new FilesystemLoader(APP_ROOT . '/templates');
$twig = new Environment( $loader, [
    'cache' => APP_ROOT . '/template/cache',
    'debug' => DEV_MODE,
] );

$twig->addGlobal('doc_root', DOC_ROOT);

if ( DEV_MODE ) {
    $twig->addExtension( new DebugExtension() );
}


$cms = new Cms($dsn, $user_name, $password);
unset($dsn, $user_name, $password);
