<?php
require dirname(__DIR__) . '/src/bootstrap.php';

$data['articles'] = $cms->getArticle()->getAll(null, true, null, 6);
$data['navigation'] = $cms->getCategory()->fetchNavigation();

$data['title'] = 'IT-News';
$data['$description'] = 'All about IT and New from Software Development and Hardware';
$data['section'] = '';

echo $twig->render('index.html', $data);



