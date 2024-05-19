<?php
session_start();

if (!isset($_SESSION['email']) || !$_SESSION['logged_in']) {
    header('Location: index2.html');
    exit;
}

$data = file_get_contents('users.json');
$users = json_decode($data, true);

$current_user_email = $_SESSION['email'];

foreach ($users as $user) {
    if ($user['email'] === $current_user_email) {
        $logged_in_user = $user;
        break;
    }
}

// Aggiunta del codice per il logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenway Website</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #eaf4eb;
            padding-top: 56px; /* Altezza della navbar */
        }

        .navbar {
            background-color: #2ecc71;
            height: 56px; /* Altezza fissa della navbar */
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: #fff;
        }

        .jumbotron {
            background-color: #2ecc71;
            color: #fff;
            padding-top: 100px; /* Regola la distanza dal top della pagina */
        }

        .section-heading {
            color: #2ecc71;
        }

        .ecology-image {
            max-width: 100%;
            height: auto;
        }

        .green-button {
            background-color: #2ecc71;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .green-button:hover {
            background-color: #27ae60;
        }
        .login-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
    <style>
        /* Aggiungi stili CSS per l'animazione */
        .firework {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: explode 0.5s linear;
        }

        @keyframes explode {
            0% {
                transform: translate(0, 0);
                opacity: 1;
                background-color: red;
            }
            50% {
                transform: translate(var(--x), var(--y));
                opacity: 0.5;
                background-color: orange;
            }
            100% {
                transform: translate(calc(var(--x) * 2), calc(var(--y) * 2));
                opacity: 0;
                background-color: yellow;
            }
        }
    </style>

</head>

<body>
<!-- Navigation Bar -->


<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">Greenway</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadAbout()">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadServices()">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
            </ul>
            <!-- Pulsante Login spostato completamente a destra -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item ml-auto">
                    <a class="nav-link" href="index2.html">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

  <div id="home">
    <!-- Home Section -->
    <div class="jumbotron text-center">
        <h1>Welcome to Greenway</h1>
        <p class="lead">Your destination for a greener world.</p>
        <img src="territoriovaltrompia.png" alt="territoriovaltrompia" class="ecology-image">
    </div>

    <!-- About Section -->
     <section id="about" class="py-5 text-center">
        <div class="container">
            <h2 class="section-heading">About Us</h2>
            <p>Sei un lavoratore che vive a due passi dall'ufficio ma predilige l'auto? È tempo di dare una scossa alle tue abitudini! Con la nostra iniziativa, non solo ti aspettano coupon e privilegi personali, ma sarai anche un vero eroe dell'ambiente scegliendo di pedalare al lavoro. Aspetta, c'è di più! Coinvolgi la tua azienda e sblocca incredibili vantaggi che la renderanno ancora più verde e porteranno benefici economici tangibili!</p>
            <img src="biciverde.png" alt="biciverde" class="ecology-image">
        </div>
     </section>
   </div>
    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand navbar-dark bg-dark">
        <div class="navbar-nav mx-auto">
            <a class="nav-item nav-link active" href="index.php">Home</a>
            <a class="nav-item nav-link" href="#" onclick="loadAbout()">Mappa</a>
            <a class="nav-item nav-link" href="#"onclick="loadServices()">Account</a>
            <a class="nav-item nav-link" href="aziende.php" >Classifica</a>
            <a class="nav-item nav-link" href="chat2/chat.php" >Chat</a>
            <div class="login-button">
                <a href="index2.html" class="btn btn-primary">Crea</a>
            </div>
        </div>
    </nav>
    <script>
        function startFireworks() {
            const container = document.getElementById('firework-container');
            for (let i = 0; i < 30; i++) {
                const firework = document.createElement('div');
                firework.classList.add('firework');
                const posX = window.innerWidth / 2;
                const posY = window.innerHeight / 2;
                const randomX = (Math.random() - 0.5) * 400;
                const randomY = (Math.random() - 0.5) * 400;
                firework.style.setProperty('--x', `${randomX}px`);
                firework.style.setProperty('--y', `${randomY}px`);
                firework.style.left = posX + 'px';
                firework.style.top = posY + 'px';
                container.appendChild(firework);
                setTimeout(() => {
                    firework.remove();
                }, 500);
            }
        }
    </script>

    <script>

function loadAbout() {
    document.getElementById('home').innerHTML = `
        <div style="display: flex;">
            <div style="flex: 1;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d89304.69325746232!2d10.122970816523392!3d45.61523238048534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e1!4m5!1s0x47817c13ee012c63%3A0x791c4f5cadda44b5!2sGardone%20Val%20Trompia%2C%20BS!3m2!1d45.6889709!2d10.18562!4m5!1s0x4781766ea2b0294d%3A0x22cd4615476aea04!2sBrescia%20BS!3m2!1d45.541552599999996!2d10.2118019!5e0!3m2!1sit!2sit!4v1697721962181!5m2!1sit!2sit" width="600" height="450" style="border:40;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div style="flex: 1; padding: 20px;">
                <div style="margin-bottom: 20px;">
                    <label for="routeLink">Link del percorso:</label>
                    <input type="text" id="routeLink" style="width: 100%; padding: 5px;">
                    <button onclick="showRouteLink()" style="padding: 10px 20px; background-color: #2ecc71; color: #fff; border: none; cursor: pointer;">Invio</button>
                </div>
                <div id="routeLinkDisplay" style="margin-bottom: 20px; display: none;">
                    <p>Link del percorso:</p>
                    <a id="displayLink" href="#" target="_blank">https://www.google.com/maps/...</a>
                </div>
                <div style="margin-bottom: 20px;">
                    <p>Distanza: 18 km</p>
                </div>
                <button  onclick="startFireworks()"style="padding: 10px 20px; background-color: #2ecc71; color: #fff; border: none; cursor: pointer;">Risquoti</button>
                <div id="firework-container"></div>
            </div>
            <img src="bici.png">
        </div>
    `;
}

function showRouteLink() {
    const routeLink = document.getElementById('routeLink').value;
    const displayLink = document.getElementById('displayLink');
    const routeLinkDisplay = document.getElementById('routeLinkDisplay');
    displayLink.href = routeLink;
    displayLink.innerText = routeLink;
    routeLinkDisplay.style.display = 'block';
}
        function loadServices() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('home').innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "dashboard.php", true);
            xhttp.send();
        }
        
</script>
</body>

</html>