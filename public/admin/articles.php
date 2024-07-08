<?php
require '../../src/bootstrap.php';

$art_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ( ! $art_id ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$articles = $cms->getArticle()->getAll(null, true, null, 6);
if ( ! $articles ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$error = filter_input(INPUT_GET, 'error') ?? '';
$success = filter_input(INPUT_GET, 'success') ?? '';

$navigation = $cms->getCategory()->fetchNavigation();
?>

<?php include '../includes/header-admin.php' ?>

<?php include '../includes/footer.php' ?>