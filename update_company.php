<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company'])) {
    $data = file_get_contents('users.json');
    $users = json_decode($data, true);

    $current_user_email = $_SESSION['email'];

    // Trova l'utente corrente
    foreach ($users as $key => $user) {
        if ($user['email'] === $current_user_email) {
            // Aggiorna l'azienda dell'utente con il valore inviato dal form
            $users[$key]['company'] = $_POST['company'];
            break;
        }
    }

    // Scrivi nel file users.json
    file_put_contents('users.json', json_encode($users));

    // Aggiorna il conteggio degli iscritti per l'azienda
    $companyData = file_get_contents('aziende.json');
    $companies = json_decode($companyData, true);

    foreach ($companies as $key => $company) {
        if ($company['name'] === $_POST['company']) {
            $companies[$key]['iscritti'] = isset($companies[$key]['iscritti']) ? $companies[$key]['iscritti'] + 1 : 1;
            break;
        }
    }

    // Scrivi nel file aziende.json
    file_put_contents('aziende.json', json_encode($companies));

    // Reindirizza alla pagina del profilo dell'utente
    header('Location: index.php');
    exit;
} else {
    echo 'Invalid request';
}
?>