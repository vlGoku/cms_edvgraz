<?php
require '../includes/functions.php';
require '../includes/db-connect.php';

$cat_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
$is_delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT) ?? null;
$sql = "DELETE FROM category WHERE id = $cat_id";
if($is_delete === 1) {
    try {
        $delete = pdo_execute($pdo, $sql);
        redirect('categories.php', ['success' => 'category successfully deleted'] );
    } catch ( PDOException $e ){
        redirect('categories.php', ['error' => 'category could not be deleted'] );
    }
}
?>

<?php include '../includes/header-admin.php'; ?>

<?php include '../includes/footer.php'; ?>
