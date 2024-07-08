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

<?php include '../includes/footer.php'; ?>
