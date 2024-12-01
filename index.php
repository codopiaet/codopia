<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="/imgs/logo.png">
    <title>Codopia</title>
</head>

<body>
    <div id="front-view">
        <h1 id="message">
            Fast Lane<br>
            <span id="span-style">to Production</span><br>
            <span id="brilliance"></span>
            <span id="insert">_</span>
        </h1>
        <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
            echo "<a href='dashboard' id='get-started' class='start-btn'>Continue with dashboard >></a>";
        }
        if (isset($_SESSION["admin_status"]) && $_SESSION["admin_status"]) {
            echo "<a href='admin' id='get-started' class='start-btn'>To admin page >></a>";
        }
        if (!isset($_SESSION["loggedin"]) || !($_SESSION["admin_status"] || $_SESSION["loggedin"])) {
            echo "<a href='login' id='get-started' class='start-btn'>Get Started >></a>";
        }
        ?>
    </div>



    <div id="foot-view">
        <a href="./dashboard/index.php" id="dashboard" class="tabs">
            <img src="imgs/dash.png" class="icons">
            <h5 class="desc">Dashboard</h5>
        </a>

        <a href="https://t.me/codopiachat" id="telegram" class="tabs">
            <img src="imgs/telegram.png" class="icons">
            <h5 class="desc">Telegram</h5>
        </a>

        <a href="https://t.me/donbosco2021" id="find-search" class="tabs">
            <img src="imgs/scool-logo.png" class="icons">
            <h5 class="desc">School</h5>
        </a>
    </div>

    <script src="script.js"></script>
</body>

</html>