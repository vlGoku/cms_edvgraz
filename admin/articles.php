<?php
require '../includes/functions.php';
require '../includes/db-connect.php';

$error = filter_input(INPUT_GET, 'error') ?? '';
$success = filter_input(INPUT_GET, 'success') ?? '';

//$sql = "SELECT id, name, navigation FROM category";
$sql = "SELECT a.id, a.title, a.summary, a.created, a.published, a.category_id, a.user_id, c.name AS category,
        CONCAT(u.forename, ' ', u.surname) as author, i.filename as image_file, i.alttext as image_alt
        FROM articles as a
        JOIN category as c ON a.category_id = c.id
        JOIN user as u ON a.user_id = u.id
        LEFT JOIN images as i ON a.images_id = i.id
        ORDER BY a.id DESC";
$articles = pdo_execute( $pdo, $sql )->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header-admin.php' ?>
<main class="container mx-auto flex justify-center flex-col items-center">
    <header class="p-10">
            <?php if ( $error ): ?>
          <p class="error text-red-500 bg-red-200 p-5 rounded-md"><?= $error ?></p>
            <?php endif ?>
            <?php if ( $success ): ?>
          <p class="success text-green-500 bg-green-200 p-5 rounded-md"><?= $success ?></p>
            <?php endif ?>
        <h1 class="text-4xl text-blue-500 mb-8">Articles</h1>
        <button class="text-white bg-blue-500 p-3 rounded-md hover:bg-pink-600"><a href="article.php">Add a new article</a></button>
    </header>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 max-w-xl mb-10">
        <thead class="text-xl text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 ">
        <tr>
            <th class="px-6 py-3">Image</th>
            <th class="px-6 py-3">Title</th>
            <th class="px-6 py-3">Created</th>
            <th class="px-6 py-3">Category</th>
            <th class="px-6 py-3">Published</th>
            <th class="px-6 py-3">Edit</th>
            <th class="px-6 py-3">Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $articles as $article ) : ?>
            <?php
/*             var_dump($article['image_file']);
            exit; */
            ?>
            <tr class="bg-white border-b dark:bg-gray-800">
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><img src="../uploads/<?php echo e ($article['image_file']) ?>" alt="<?php $article['image_alt']?>"></td>
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo e( $article['title'] ) ?></td>
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo e( format_date($article['created'])) ?></td>
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo e( $article['category'] ) ?></td>
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo e( $article['published'] ) ? 'Yes' : 'No' ?></td>
                <td class="px-6 py-4 font-medium text-pink-600 whitespace-nowrap"><a href="article.php?id=<?php echo $article['id'] ?>">Edit</a></td>
                <td class="px-6 py-4 font-medium text-blue-600 whitespace-nowrap"><a href="article-delete.php?id=<?php echo $article['id'] ?>">Delete</a></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</main>
<?php include '../includes/footer.php' ?>