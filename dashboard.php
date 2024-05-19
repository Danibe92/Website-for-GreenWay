<?php
session_start();

if (!isset($_SESSION['email']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$data = file_get_contents('users.json');
$users = json_decode($data, true);

$current_user_email = $_SESSION['email'];
$logged_in_user = null;

foreach ($users as $user) {
    if ($user['email'] === $current_user_email) {
        $logged_in_user = $user;
        break;
    }
}

// Calcola la percentuale di completamento dei chilometri
$completionPercentage = ($logged_in_user['kilometers'] / 1000) * 100; // Ad esempio, si presume che 1000 sia la soglia massima

?>

<!DOCTYPE html>
<html lang="en">
<!-- ... Il resto del tuo codice ... -->
<head>
    <style>
        /* ... Altri stili ... */
        .centered-text {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="centered-text">
        <h2>Welcome, <?php echo $logged_in_user['firstname'] . ' ' . $logged_in_user['lastname']; ?>
        </h2>
        <p>Your email:
            <?php echo $logged_in_user['email']; ?>
        </p>
        <p>Your Km:
            <?php echo $logged_in_user['kilometers']; ?>
        </p>
        <br></br>
    </div>

    <br></br>
    <!-- Aggiunta della barra di completamento in base ai chilometri -->
    <div style="position: fixed; top: 120px; right: 10px; z-index: 999;">
            <img src="regalo.webp" alt="Logo" style="top:40px;right:20px; position:absolute;width: 150px; height: 100px;">
</div>
<div style="display: flex; flex-direction: column; align-items: center; margin-top: 20px;">
    <div style="width: 80%; background-color: #ddd;">
        <div style="width: <?php echo $completionPercentage; ?>%; height: 30px; background-color: #4CAF50;">
        </div>
    </div>
    <div style="width: 80%; display: flex; justify-content: space-between;">
        <span>0 km</span>
        <span>1000 km
        </span>
    </div>
</div>

<br></br>
    
<?php
$companies = json_decode(file_get_contents('aziende.json'), true);
$hasCompany = array_key_exists('company', $logged_in_user);

if ($hasCompany): ?>
        <h3 class="centered-text">La tua azienda: <?php echo $logged_in_user['company']; ?></h3>
<?php elseif (!$hasCompany): ?>
    <form action="update_company.php" method="post">
        <label for="company">Select Company:</label>
        <select name="company" id="company" class="form-control">
            <option value="" disabled selected>Select your company</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?php echo $company['name']; ?>">
                    <?php echo $company['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit" class="btn btn-primary">Save Company</button>
    </form>
<?php endif; ?>


    <!-- Altre informazioni dell'utente possono essere visualizzate qui -->
</body>

</html>