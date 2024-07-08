<?php
require '../../src/bootstrap.php';

$cat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ( ! $cat_id ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$categories = $cms->getCategory()->getAll();
if ( ! $categories ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$error = filter_input(INPUT_GET, 'error') ?? '';
$success = filter_input(INPUT_GET, 'success') ?? '';

$articles = $cms->getArticle()->getAll( $cat_id );
$navigation = $cms->getCategory()->fetchNavigation();
$section = $cat_id;
?>

<?php include '../includes/header-admin.php' ?>

<?php include '../includes/footer.php' ?>