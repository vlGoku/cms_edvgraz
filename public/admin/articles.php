<?php
require '../../src/bootstrap.php';

$art_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ( ! $art_id ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$articles = $cms->getArticle()->fetch($art_id);
if ( ! $articles ) {
    include APP_ROOT . '/public/page_not_found.php';
}

$articles = $cms->getArticle()->getAll( $cat_id );
$navigation = $cms->getCategory()->fetchNavigation();
$title = $category['name'];
$description = $category['description'];
$section = $cat_id;
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