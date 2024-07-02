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
<main class="container mx-auto flex justify-center flex-row items-center">
    <h2 class="text-xl text-blue-500 mb-8">Are you sure you want to delete this category? </h3>
    <button class="text-white bg-red-500 p-3 rounded-md hover:bg-pink-600"><a href="category-delete.php?delete=1&id=<?= $cat_id ?>">Yes</button>
    <button class="text-white bg-blue-500 p-3 rounded-md hover:bg-pink-600"><a href="categories.php">No</a></button>
</main>
<?php include '../includes/footer.php'; ?>
