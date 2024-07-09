<?php
require '../../src/bootstrap.php';

is_admin($session->role);

$data['articles'] = $cms->getArticle()->fetchAll();
$data['error'] = filter_input(INPUT_GET, 'error') ?? '';
$data['success'] = filter_input(INPUT_GET, 'success') ?? '';

echo $twig->render('admin/articles.html', $data);