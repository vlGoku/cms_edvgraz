<?php
require '../includes/functions.php';
require '../includes/db-connect.php';

$article_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? null;
$is_delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT) ?? null;
$sql = "SELECT id, title, created, category_id, user_id, images_id, published FROM articles WHERE id = $article_id";
$article = pdo_execute($pdo, $sql)->fetch(PDO::FETCH_ASSOC);
if($is_delete === 1) {
    if($article['images_id']){
        $images_id = $article['images_id'];
        $sql = "SELECT filename FROM images WHERE id = $images_id";
        $image = pdo_execute($pdo, $sql)->fetch(PDO::FETCH_ASSOC);
        $sql = "UPDATE articles SET images_id = NULL WHERE id = $article_id";
        $image_to_be_null = pdo_execute($pdo, $sql);
        $sql = "DELETE FROM images WHERE id = $images_id";
        $iamge_to_be_deleted = pdo_execute($pdo, $sql);
        unlink("../uploads/" . $image['filename']);
    }
    try {
        $sql = "DELETE FROM articles WHERE id = $article_id"; 
        $delete = pdo_execute($pdo, $sql);
        redirect('articles.php', ['success' => 'article successfully deleted'] );
    } catch ( PDOException $e ){
        redirect('articles.php', ['error' => 'article could not be deleted'] );
    }
}
?>

<?php include '../includes/header-admin.php'; ?>
<main class="container mx-auto flex justify-center flex-row items-center">
    <h2 class="text-xl text-blue-500 mb-8">Are you sure you want to delete this article? </h3>
    <button class="text-white bg-red-500 p-3 rounded-md hover:bg-pink-600"><a href="article-delete.php?delete=1&id=<?= $article_id ?>">Yes</button>
    <button class="text-white bg-blue-500 p-3 rounded-md hover:bg-pink-600"><a href="articles.php">No</a></button>
</main>
<?php include '../includes/footer.php'; ?>