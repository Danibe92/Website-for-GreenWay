<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('users.json');
    $users = json_decode($data, true);

    $newUser = array(
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'email' => $_POST['email'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'kilometers' => 0
    );

    $users[] = $newUser;
    file_put_contents('users.json', json_encode($users));

    echo 'Registration successful';
}
?>
