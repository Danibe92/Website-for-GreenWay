<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('users.json');
    $users = json_decode($data, true);

    $loginEmail = $_POST['loginEmail'];
    $loginPassword = $_POST['loginPassword'];

    foreach ($users as $user) {
        if ($user['email'] === $loginEmail && password_verify($loginPassword, $user['password'])) {
            $_SESSION['email'] = $loginEmail;
            $_SESSION['logged_in'] = true;
            // Altre operazioni possono essere eseguite qui

            header('Location: index.php'); // Reindirizza alla pagina del dashboard dopo il login
            exit();
        }
    }
    echo 'Invalid email or password';
}
?>