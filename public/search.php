<?php
require 'includes/db-connect.php';
require 'includes/functions.php';

$search_term = filter_input(INPUT_GET, 'search');
$per_page = filter_input(INPUT_GET, 'per_page', FILTER_VALIDATE_INT) ?? 3;
$offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT) ?? 0;
$articles = [];
$count = 0;

$sql = 'SELECT id, name FROM category WHERE navigation = 1';
$navigation = pdo_execute( $pdo, $sql )->fetchAll(PDO::FETCH_ASSOC);

$section = '';
$title = 'Search Results for ' . $search_term;
$description = 'Search Results for ' . $search_term . ' the IT-News-Blog';


if ( ! empty( $search_term ) ) {
    $sql = 'SELECT COUNT(*) FROM articles
            WHERE published = 1 AND (title LIKE :search OR summary LIKE :search OR content LIKE :search)';
    $count = pdo_execute( $pdo, $sql, ['search' => "%$search_term%"] )->fetchColumn();
    
    if ( $count > 0 ) {
        $sql = "SELECT a.id, a.title, a.summary, a.category_id, a.user_id, c.name AS category,
                CONCAT(u.forename, ' ', u.surname) AS author,
                i.filename AS image_file,
                i.alttext AS image_alt
                FROM articles AS a
                JOIN cms_edvgraz.category c on a.category_id = c.id
                JOIN user as u on a.user_id = u.id
                LEFT JOIN images as i on a.images_id = i.id
                WHERE a.published = 1 AND (a.title LIKE :search OR a.summary LIKE :search OR a.content LIKE :search)
                ORDER BY a.id DESC
                LIMIT :per_page
                OFFSET :offset";
        
        $articles = pdo_execute( $pdo, $sql, [
            'search' => "%$search_term%",
            'per_page' => (int) $per_page,
            'offset' => (int) $offset
        ])->fetchAll(PDO::FETCH_ASSOC);
    }

}

if ( $count > $per_page ) {
    $total_pages = ceil($count / $per_page );
    $current_page = ceil($offset / $per_page ) + 1;
}
?>

<?php include 'includes/header.php'; ?>

<?php include 'includes/footer.php'; ?>
