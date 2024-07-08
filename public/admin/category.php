<?php
require '../../src/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? '';
$errors = [
    'issue' => '',
    'name' => '',
    'description' => '',
];

$category = [
    'id' => '',
    'name' => '',
    'description' => '',
    'navigation' => '',
];

//Wenn eine ID vorhanden ist, dann wird die Kategorie aus der Datenbank geladen
if ( $id ) {
    $category = $cms->getCategory()->fetch($id);
    if ( ! $category ) {
        redirect('categories.php', ['error' => 'category not found']);
    }
}

// Wenn das Formular mit Daten abgeschickt wurde, dann werden die Daten validiert und gespeichert
if ( $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Die Daten werden aus dem Formular ausgelesen und validiert
    $category['name'] = filter_input(INPUT_POST, 'name');
    $category['description'] = filter_input(INPUT_POST, 'description');
    $category['navigation'] = filter_input(INPUT_POST, 'navigation', FILTER_VALIDATE_BOOLEAN);
    // Die Daten werden auf Länge und vorhanden validiert
    $errors['name'] = Validate::is_text( $category['name'], 1, 50) && ( ! empty( $category['name'] ) ) ? '' : 'Name must be between 1 and 50 characters';
    $errors['description'] = Validate::is_text( $category['description'], 1, 254) && ( ! empty( $category['description'] ) ) ? '' : 'Description must be between 1 and 254 characters';
    //Fehler werden in eine Zeichenkette zusammengefasst
    $problems = implode( $errors );

    // Wenn es keine Fehler gibt, wird die Kategorie gespeichert und der Benutzer zur Kategorie-Liste umgeleitet
    if ( ! $problems ) {
        // DIe zu speichernden Daten werden in ein Array zusammengefasst um später die Platzhalter zu ersetzen
        $bindings = [
            'name' => $category['name'],
            'description' => $category['description'],
            'navigation' => $category['navigation']
        ];
        try {
            if ( $id ) {
                $bindings['id'] = $id;
                $cms->getCategory()->update( $bindings );
                redirect('categories.php', ['success' => 'category successfully saved']);
            } else {
                $cms->getCategory()->push($bindings);
                redirect('categories.php', ['success' => 'category successfully saved']);
            }
        } catch (PDOException $e) {
            $errors['issue'] = 'Name already in use';
        }   
    } else {
        $errors['issue'] = 'Please correct the following issues: ' . $problems;
    }
}

?>

<?php include '../includes/header-admin.php' ?>

<?php include '../includes/footer.php' ?>