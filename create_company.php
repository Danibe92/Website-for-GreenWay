<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('aziende.json');
    $companies = json_decode($data, true);

    $newCompany = array(
        'name' => $_POST['name'],
        'address' => $_POST['address'],
        'email' => $_POST['email'],
        'kitrovare'=> $_POST[0],
        'iscritti' => $_POST[0],
    );

    $companies[] = $newCompany;
    file_put_contents('aziende.json', json_encode($companies));

    echo 'Company created successfully';
}
?>