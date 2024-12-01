<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/bootstrap.min.css">
    <script src="/static/bootstrap.bundle.min.js"></script>
    <title>User Profile</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .profile-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .profile-card .profile-img {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            border-radius: 10px 10px 0 0;
        }

        .profile-card .profile-info {
            padding: 20px;
        }

        .profile-card .profile-info h2 {
            margin-bottom: 10px;
        }

        .profile-card .profile-info .badge {
            margin-right: 5px;
        }

        .user-initial {
            font-size: 75px;
            border-radius: 50%;
            padding: 30px;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <?php
    session_start();
    require_once "../admin/scripts/validation.php";
    require_once "../admin/scripts/classes.php";

    if (!$_GET["id"]) header("Location: /dashboard");

    if (!$_GET["id"]) die(1);
    if ($_SERVER['REQUEST_METHOD'] !== "GET") die();
    if (!validateInteger($_GET["id"])) exit;
    $userID = $_GET["id"];
    $db = new DataBase();
    $user = $db->executeSql("select username, points, bio from users where id=?", [$userID], true);

    if ($user["rows"] == 0) die("<div class='alert alert-danger text-center'>User not found!</div>");
    ?>
    <div class="container-lg d-flex justify-content-center" style="margin-top: 50px;">
        <div class="profile-card col-md-8">
            <!-- <div class="profile-img" style="background-image: url('/imgs/profile-background.jpg');"></div> -->
            <div class="profile-info">
                <div class="user-initial text-light"><?php echo strtoupper($user[0]["username"][0]); ?></div>
                <h2>Username: @<?php echo $user[0]["username"] ?></h2>
                <p>Points: <?php echo $user[0]["points"] ?></p>
                <p>Bio: <?php echo $user[0]["bio"] ?></p>
                <div>
                    <?php
                    list($text, $class) = getBadge((int)$user[0]["points"]);
                    echo "<div class=\"badge $class\" style='font-size: 20px;'>$text</div>";
                    echo $_SESSION["admin_status"] ? "<div class='badge badge-success'>Admin</div>" : "";
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>