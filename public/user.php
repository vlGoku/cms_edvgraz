<?php
require '../src/bootstrap.php';

$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ( ! $user_id ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$user = $cms->getUser()->fetch($user_id);
if ( ! $user ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$navigation = $cms->getUser()->fetchNavigation();
$section = $user_id;
?>

<?php include './includes/header.php'; ?>

<?php include './includes/footer.php'; ?>
