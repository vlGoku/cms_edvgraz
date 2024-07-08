<?php
require '../includes/functions.php';
require '../includes/db-connect.php';
require '../includes/validate.php';

use EdvGraz\Validation\Validate;

//Variablen initialisieren für Bildupload
$path_to_img = '/uploads/';
$allowed_types = [ 'image/jpeg', 'image/png'];
$allowed_ext = ['jpg', 'jpeg', 'png'];
$max_size = 1080 * 1920 * 2;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? '';
$tmp_path = $_FILES['filename']['tmp_name'] ?? '';
$save_to = '';

$article = [
    'id' => $id,
    'title' => '',
    'summary' => '',
    'content' => '',
    'published' => false,
    'category_id' => 0,
    'user_id' => 0,
    'images_id' => null,
    'filename' => '',
    'alttext' => '',
];

$errors = [
    'issue' => '',
    'title' => '',
    'summary' => '',
    'content' => '',
    'user' => '',
    'category' => '',
    'filename' => '',
    'alttext' => '',
];

// Lade alle Kategorien von der Datenbank
$sql = "SELECT id, name FROM category";
$categories = pdo_execute( $pdo, $sql)->fetchAll(PDO::FETCH_ASSOC);
$sql = "SELECT id, forename, surname FROM user";
$users = pdo_execute( $pdo, $sql )->fetchAll(PDO::FETCH_ASSOC);

if ( $id ) {
    $sql = "SELECT a.id, a.title, a.summary, a.content, a.category_id,
            a.user_id, a.images_id, a.published,
            i.filename, i.alttext FROM articles a
            LEFT JOIN images i ON a.images_id = i.id
            WHERE a.id = :id";
    $article = pdo_execute( $pdo, $sql, ['id' => $id] )->fetch(PDO::FETCH_ASSOC);
    if ( ! $article ) {
        redirect('articles.php', ['error' => 'article not found'] );
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Bild auslesen
    if ( isset( $_FILES['filename'] ) ) {
        $image = $_FILES['filename'];
        //Bildgröße validieren
        $errors['filename'] = $image['error'] === 1 ? 'The image is to large ' : '';
        // Wenn ein Bild hochgeladen wurde, dann wird es validiert
        if ($tmp_path && $image['error'] === UPLOAD_ERR_OK ) {
            // Alt-Text wird gesetzt 
            $article['alttext'] = filter_input(INPUT_POST, 'alttext');
            //Alt-Text validieren
            $errors['alttext'] = is_text($article['alttext'], 1, 254) ? '' : 'Alt text must be between 1 and 254 characters';
            //Bildtyp wird validiert
            $typ = mime_content_type($tmp_path);
            $errors['filename'] .= in_array($typ, $allowed_types) ? '' : 'The file type is not allowed';
            //Bildendung wird validiert
            $extension = pathinfo(strtolower($image['name'] ), PATHINFO_EXTENSION);
            $errors['filename'] .= in_array($extension, $allowed_ext) ? '' : 'The file extension is not allowed';
            //Bildgröße wird validiert
            $errors['filename'] .= $image['size'] > $max_size ? 'The image exceeds the maximum opload size' : '';
            //Wenn es keine Fehler gibt, wird ein Speicherort für das Bild festegelegt
            if( $errors['filename'] === '' && $errors['alttext'] === '' ){
                $article['filename'] = $image['name'];
                $save_to = get_file_path($image['name'], $path_to_img);
            }
        }
    }
    //Daten aus dem Formular auslesen
    $article['title'] = filter_input(INPUT_POST, 'title');
    $article['summary'] = filter_input(INPUT_POST, 'summary');
    $article['content'] = filter_input(INPUT_POST, 'content');
    $article['user_id'] = filter_input(INPUT_POST, 'user', FILTER_VALIDATE_INT);
    $article['category_id'] = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
    $article['published'] = filter_input(INPUT_POST, 'published', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

    //HTML Code wird bereinigt
    $purifier = new HTMLPurifier();
    //Es werden nur die in der set Methode genannten HTML-Tags zu gelassen
    $purifier->config->set('HTML.Allowed', 'p,br,strong,em,a[href],i,u,ul,ol,li,img[src|alt]');
    //Purifier wird auf den Content angewendet
    $article['content'] = $purifier->purify($article['content']);

    //Error Meldung erstellen und zusätzliche Validierung
    $errors['title'] = is_text( $article['title'] ) ? '' : 'Title must be between 1 and 100 characters';
    $errors['summary'] = is_text($article['summary'], 1, 200) ? '' : 'Summary must be between 1 and 200 characters';
    $errors['content'] = is_text($article['content'], 1, 10000) ? '' : 'Content must be between 1 and 10.000 characters';
    $errors['user'] = is_user_id($article['user_id'], $users) ? '' : 'User not found';
    $errors['category'] = is_category_id($article['category_id'], $categories) ? '' : 'Category not found';

    $problems = implode($errors);

    if ( ! $problems ){
        //bindings beinhaltet alle Variablen die der Funktion Statement::bindValue übergeben werden (oder auch Statement::execute als Array)
        $bindings = $article;
        try {
            //Transaktion starten
            $pdo->beginTransaction();
            //Wenn ein Bild hochgeladen wurde, wird es gespeichert
            if( $save_to ) {
                scale_and_copy($tmp_path, $save_to);

                $sql = "INSERT INTO images (filename, alttext) VALUES (:filename, :alttext)";
                $stmt = pdo_execute($pdo, $sql, ['filename' => $article['filename'], 'alttext' => $article['alttext'] ] );
                //lastInsertId gibt die Id des zuletzt eingefügten Datensatzes zurück
                $bindings['images_id'] = $pdo->lastInsertId();
            }
            //Da ab hier die Bildtabelle aktualisiert wurde, werden die Bilddaten aus dem bindings Array entfernt.
            //Für das INSERT (Anlegen eines neuen Datensatzes) wird die id automatich mit autoinkrement erstellt
            //und muss deshlab hier aus den bindngs entfernt werden.
            unset($bindings['filename'], $bindings['alttext'], $bindings['id']);
            $sql = "INSERT INTO articles (title, summary, content, category_id, user_id, published, images_id)
                    VALUES (:title, :summary, :content, :category_id, :user_id, :published, :images_id)";
            //Block, wenn ein Artikel bearbeitet wurde
            if ( $id ) {
                // Wenn die ID vorhanden ist, wird ein Update durchgeführt und die id wird wieder in das bindings Array aufgenommen.
                $bindings['id'] = $id;
                $sql = "UPDATE articles SET title = :title, summary = :summary, content = :content,
                        category_id = :category_id, user_id = :user_id, published = :published, images_id = :images_id WHERE id = :id";
            }
            $stmt = pdo_execute( $pdo, $sql, $bindings );
            //Transaktion abschließen
            $pdo->commit();
            redirect('articles.php', ['success' => 'article successfully saved' ] );
        } catch ( PDOException $e ) {
            // Wenn ein Fehler auftritt, wird die Transkation zurückgerollt und der Fehler ausgegeben
            $pdo->rollBack();
            $errors['issue'] = $e->getMessage();
        }
    }

}
?>

<?php include '../includes/header-admin.php'; ?>

<script>
    tinymce.init({
        selector: '#content',
        menubar: false,
        toolbar: 'bold italic underline link',
        plugins: 'link',
        link_title: false
    })
</script>
<?php include '../includes/footer.php'; ?>
