<?php
require '../../src/bootstrap.php';

is_admin($session->role);

$data['categories'] = $cms->getCategory()->fetchAll();
$data['error'] = filter_input(INPUT_GET, 'error') ?? '';
$data['success'] = filter_input(INPUT_GET, 'success') ?? '';

echo $twig->render('admin/categories.html', $data);
