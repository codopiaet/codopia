<?php

declare(strict_types=1);
session_start();
if (!$_SESSION["loggedin"]) {
  header("Location: /login");
  exit;
}

require("../admin/scripts/dashboard_setup.php");
require_once("../admin/scripts/classes.php");

$sessionUserId = $_SESSION["userid"];

$statementForThePointsAndUploads = $con->prepare("select points, uploads from users where id=?");
$statementForThePointsAndUploads->execute([$_SESSION["userid"]]);
$statementForThePointsAndUploads->setFetchMode(PDO::FETCH_ASSOC);
$resultForThePointsAndUploads = $statementForThePointsAndUploads->fetchAll();
$resultForThePointsAndUploads = $resultForThePointsAndUploads[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/imgs/logo.png">
  <link rel="stylesheet" href="side.css">
  <link rel="stylesheet" href="fix.css">
  <link rel="stylesheet" href="loader.css">
  <!-- <link rel="stylesheet" href="./dark.css"> -->
  <link rel="stylesheet" href="/static/bootstrap.min.css">
  <title>Dashboard - <?php echo $_SESSION["username"] ?></title>
  <style>
    .highlight {
      background-color: yellowgreen;
    }

    /* THIS PLACE IS NEEDED FOR THE THEME AND FONT CHANGES */
  </style>
</head>

<body class="d-flex justify-content-center align-items-center">

  <div id="loader-container">
    <div class="loader"></div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <?php
      //MARK: SIDEBAR
      ?>
      <!-- #1e2d40 -->
      <div id="sidebar" class="sidebar d-none d-md-block col-md-3 sidebar-g position-relative shadow" style="background-color: #1e2d40;">
        <div class="logo d-flex h-25 justify-content-center align-items-center">
          <a href="../"><img src="/imgs/text-logo.png" style="width: 187.5px;" alt="codopia logo"></a>
        </div>
        <ul class="nav mt-5 flex-column">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
              </svg> Home</a>
          </li>
          <li class="nav-item">
            <a href="../test_page/" class="nav-link"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-code-slash" viewBox="0 0 16 16">
                <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0" />
              </svg> Test</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./projects/">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-buildings" viewBox="0 0 16 16">
                <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z" />
                <path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z" />
              </svg> Projects</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./upload/">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-upload" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
              </svg> Upload</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./feedback/">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-journal-arrow-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 11a.5.5 0 0 0 .5-.5V6.707l1.146 1.147a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 .5.5" />
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
              </svg> Feedback</a>
          </li>
          <li class="nav-item position-absolute bottom-0 mb-3">
            <div class="container col-12">
              <button type="button" class="btn btn-dark border-0" data-bs-toggle="modal" data-bs-target="#coolModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-list" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg> More
              </button>
            </div>
          </li>
        </ul>
      </div>

      <?php
      //MARK: MOBILE VIEW NAVBAR
      ?>
      <div id="mob-view" class="navbar navbar-expand-md navbar-light bg-light d-md-none shadow border border-1" style="z-index: 999;">
        <ul class="mt-3 p-0" id="mv-ul">
          <li class="nav-item">
            <a href="#" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 bi bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
              </svg></a>
            <!-- HOME -->
          </li>

          <li>
            <a href="../test_page/" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi me-2 bi-balloon" viewBox="0 0 16 16">
                <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0" />
              </svg></a>
            <!-- TEST -->
          </li>

          <li>
            <a href="./projects/" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 bi bi-buildings" viewBox="0 0 16 16">
                <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z" />
                <path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z" />
              </svg></a>
            <!-- PROJECTS -->
          </li>

          <li>
            <a href="./upload/" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 bi bi-upload" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
              </svg></a>
            <!-- UPLOADS -->
          </li>

          <li>
            <a href="./feedback/" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-journal-bookmark me-2" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8" />
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
              </svg></a>
            <!-- FEEDBACKS -->
          </li>

          <li>
            <button class="btn btn-transparent border-0 mb-5 p-0" data-bs-toggle="modal" data-bs-target="#coolModal"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
              </svg></button>
            <!-- SETTINGS -->
          </li>
        </ul>
      </div>

      <?php
      //MARK: MAIN CONTAINER
      ?>
      <div class="container col-md-9 main-content-g position-relative">

        <?php
        //MARK: Search
        ?>

        <div class="row sticky-top py-3 d-flex">
          <div class="container col-9 d-flex justify-content-center align-items-center">
            <input type="text" class="form-control shadow Search" placeholder="Search" id="search">
          </div>
          <div class="container col-3 d-block d-md-none d-flex justify-content-center align-items-center">
            <img src="/imgs/logo.png" width="75" alt="">
          </div>
        </div>

        <div class="row m-0 p-0 justify-content-center">

          <?php
          //MARK: Profile
          ?>

          <div class="row justify-content-center col-md-12">
            <div class="col-12 col-md-10 mt-2 text-center">
              <div class="card shadow">
                <div class="row g-0">
                  <div class="col-md-4 bg-dark text-white d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                      <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                    </svg>
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h3 class="card-title">@<?php echo $_SESSION["username"] ?></h3>
                      <h5 class="card-subtitle mb-2 text-muted">
                        <?php echo $_SESSION["full_name"] ?>
                      </h5>
                      <p class="mb-2 text-muted"><?php echo $_SESSION["bio"] ?></p>

                      <div class="row">
                        <div class="col-md-6">
                          <p class="card-text"><strong>XP:</strong>
                            <?php echo "<span id='total-xp'>{$resultForThePointsAndUploads['points']}</span>" ?>
                          </p>
                        </div>
                        <div class="col-md-6">
                          <p class="card-text"><strong>Uploads:</strong>
                            <?php echo $resultForThePointsAndUploads["uploads"] ?>
                          </p>
                        </div>
                      </div>
                      <div class="mt-3">
                        <a href="./edit-profile/" class="btn btn-primary">Edit Profile</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php
            //MARK: Event
            ?>

            <div class="col-12 col-md-10 mt-2">
              <div class="card shadow">
                <div class="card-body">
                  <div class="card-title">
                    <?php
                    $db = new DataBase();
                    $r = $db->executeSql("select count(*) as count from events", [], true);
                    if ((int)$r[0]["count"] === 0) {
                      echo '<h2 class="text-center text-dark fw-bold">No events released so far</h2>
                  </div>
                  <div class="card-body">
                      ';
                    } else if ((int)$r[0]["count"] > 0) {
                      echo "
                      <h1 class=\"text-center text-dark fw-bold\">{$r[0]['count']} Event found</h1>
                      </div>
                  <div class=\"card-body\">
                      <h4 class=\"text-center p-2 text-muted\">Click read more for more information</h4>
                      <div class=\"container d-flex justify-content-center\">
                      <button class=\"btn btn-primary btn-lg col-md-6\" data-bs-toggle=\"modal\" data-bs-target=\"#eventModal\">
                        <i class=\"bi bi-info-circle me-10\"></i>Read More
                      </button>
                    </div>
                      ";
                    }
                    ?>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php
          //MARK: Project
          ?>

          <div class="row mb-5">
            <div class="container p-2">
              <?php
              $statementForTheProjects = $con->prepare("SELECT * FROM projects INNER JOIN users ON projects.user_id = users.id ORDER BY project_time DESC");
              $statementForTheProjects->execute();
              $resultsForTheProjects = $statementForTheProjects->fetchAll(PDO::FETCH_ASSOC);

              if ($statementForTheProjects->rowCount() === 0) {
                echo '<div class="text-muted text-center">No projects uploaded so far</div>';
              }
              foreach ($resultsForTheProjects as $eachProjects) {
                $eachProjectId = (string) "project_{$eachProjects['project_id']}";

                list($badgeName, $badgeColor) = getBadge((int)$eachProjects["points"]);
                $admin = $eachProjects["is_admin"] ? "<div class='badge bg-success col-2 d-flex justify-content-center align-items-center' style='margin-right: 6px'>Admin</div>" : "";

                echo "
                
                <div class='container shadow col-md-8 rounded-3 mt-5 mb-5 border border-1 project-style'>
                <div class='row d-flex p-2'>
                  $admin
                  <div class='badge $badgeColor col-2 d-flex justify-content-center align-items-center' style='margin-right: 6px'>$badgeName</div>
                </div>
                  <div class='text-center p-2'>
                    <a href='profile.php?id={$eachProjects['user_id']}' class='nav-link'>
                      <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' fill='currentColor' class='bi text-primary bi-person' viewBox='0 0 16 16'>
                        <path d='M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z' />
                      </svg>
                      <p class='search-item username'>@{$eachProjects['username']}<p></a>
                    <a href='profile.php?id={$eachProjects['user_id']}' class='nav-link'>
                      <h3 class='search-item full_name'>{$eachProjects['full_name']}</h3>
                    </a>
                    <div class='mt-1 text-center'>
                      <p class=''>{$eachProjects['bio']}</p>
                    </div>
                  </div>
                  <div class='text-center p-2'>
                    <h1 class='text search-item project_name'>{$eachProjects['project_name']}</h1>
                  </div>
                  <div class='text-center p-4'>
                    <p class='text search-item project_detail'>{$eachProjects['project_detail']}</p>
                  </div>
                  <div class='p-4 d-flex justify-content-center align-items-center'>
                    <a href='download.php?file={$eachProjects['file_path']}' class='btn btn-lg shadow btn-success p-2'>Download</a>
                    
                  </div>
                  <div class='row'>
                    <div class='col p-4'>
          
                        <button type='submit' class='btn btn-transparent text-danger g-test-btn'>
                          <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-heart-fill' viewBox='0 0 16 16'>
                            <path class='liker' project_id='{$eachProjects['project_id']}' fill-rule='evenodd' d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z' />
                          </svg>
                          <br>  
                          <span class='text-danger'>{$eachProjects['likes']}</span>
                        </button>
          
                    </div>
                    <div class='col text-end p-4'>
                      <button id='project_{$eachProjects['project_id']}' class='btn btn-transparent text-dark' onclick='(copyLinkToClipboard(\"{$eachProjectId}\"))'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-share' viewBox='0 0 16 16'>
                          <path d='M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z' />
                        </svg>
                        <br>
                        <span class='small'>Share</span>
                      </button>
                    </div>
                  </div>
                </div>
          
                </div>
                ";
              }
              ?>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>

  <?php
  //MARK: MODALS
  ?>

  <?php
  //MARK: Setting
  ?>
  <div class="modal fade" id="coolModal" tabindex="-1" role="dialog" aria-labelledby="coolModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content text-center col-md-10 mb-5">
        <div class="modal-header">
          <h5 class="modal-title" id="coolModalLabel">Settings</h5>
          <button type="button" class="close shadow-none bg-white border border-dark rounded" data-bs-dismiss="modal" aria-label="Close">
            <span class="shadow-none" aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center d-flex flex-column">
          <!--<div class="dropdown">
            <button class="btn border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Theme
            </button>
            <ul class="dropdown-menu">
              <li><button class="btn btn-transparent col-md-12" onclick="setDarkMode()"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-moon" viewBox="0 0 16 16">
                    <path d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278M4.858 1.311A7.27 7.27 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.32 7.32 0 0 0 5.205-2.162q-.506.063-1.029.063c-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286" />
                  </svg> Dark</button></li>
              <li><Button class="btn btn-transparent col-md-12"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-brightness-high" viewBox="0 0 16 16">
                    <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6m0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708" />
                  </svg> Light</Button></li>

            </ul>
          </div>-->
          <div class="dropdown">
            <button class="btn border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Fonts
            </button>
            <ul class="dropdown-menu">
              <li><button class="btn btn-transparent col-md-12 font-changer" style="font-family: 'Segoe UI', serif;">Segoe
                  UI</button></li>
              <li><button class="btn btn-transparent col-md-12 font-changer" style="font-family: 'Courier New', Courier, monospace;">Courier New</button></li>
              <li><button class="btn btn-transparent col-md-12 font-changer" style="font-family: Verdana, Geneva, Tahoma, sans-serif;">Verdana</button></li>
              <li><button class="btn btn-transparent col-md-12 font-changer" style="font-family: 'Comic Sans MS';">Comic Sans MS</button></li>
              <li><button class="btn btn-transparent col-md-12 font-changer" style="font-family: 'Luminari';">Luminari</button></li>
            </ul>
          </div>
          <button class="col-12 mt-3 btn btn-dark" onclick="share()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-share" viewBox="0 0 16 16">
              <path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.5 2.5 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5m-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3" />
            </svg> Share</button>

          <a href="../logout/"><button class="col-12 btn mt-2 btn-danger">Log Out</button></a>
        </div>
        <div class="modal-footer">
          <a href="#" class="nav-link">Powered by <img src="../imgs/logo.png" style="height: 50px;" alt=""></a>
        </div>
      </div>
    </div>
  </div>


  <?php
  //MARK: Event
  ?>
  <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <?php
      $statementForTheEvent = $con->prepare("SELECT * FROM events ORDER BY deadline DESC LIMIT 1");
      $statementForTheEvent->execute();
      $resultForTheEvent = $statementForTheEvent->fetch(PDO::FETCH_ASSOC);

      if ($resultForTheEvent) {
        $date = strtotime($resultForTheEvent["deadline"]);
        $formattedDate = date("l, F d, Y", $date);
        $acceptedBy = json_decode($resultForTheEvent["accepted_by"], true);
        if (in_array($sessionUserId, $acceptedBy)) {
          $joinBgColor = "#dc3545"; // if already accepted
          $joinText = "Reject";
        } else {
          $joinBgColor = "#0d6efd"; // if already accepted
          $joinText = "Join";
        }
        echo "
              
          <div class='modal-content'>
            <form action='./acceptHandler.php' method='post' id='event-form' event_id='{$resultForTheEvent['event_id']}' xp='{$resultForTheEvent['xp']}'>
              <div class='modal-header'>
                <h5 class='modal-title' id='eventModalLabel'><b>Upcoming Event</b></h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>

              <div class='modal-body'>
                <h5><b>{$resultForTheEvent['event_name']}</b></h5>
                <p>{$resultForTheEvent['event_desc']}</p>
                <h5><b>Event Details</b></h5>

                <ul>
                  <li>Date: {$formattedDate}</li>
                  <li>XP: <span id='event-xp'>{$resultForTheEvent['xp']}</span></li>
                  <div class='form-check'>
                    <input name='condition' class='form-check-input condition' required type='checkbox' id='flexCheckDefault'>
                    <label class='form-check-label' for='flexCheckDefault'>
                      I have acknowledged the conditions
                    </label>
                  </div>
                </ul>
              </div>

              <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                <div id='liveAlertPlaceholder'></div>
                <button type='submit' class='btn join-btn' style='color: white; background-color: {$joinBgColor};'>{$joinText}</button>
              </div>
            </form>
          </div>
    
          ";
      } else {
        echo "
              
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title' id='eventModalLabel'>Upcoming Event</h5>
              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
              <p>Currently, there is no available event</p>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
              <div id='liveAlertPlaceholder'></div>
              </form>
            </div>
          </div>

          ";
      }

      ?>
    </div>
  </div>

</body>

<script>
  if (localStorage.getItem("font-family")) document.querySelector("style").innerHTML = `*{font-family:"${localStorage.getItem("font-family")}";}`
</script>
<script src="./loader.js"></script>
<script src="./script.js"></script>
<script src="./request.js"></script>
<script src="/static/sweetalert2.js"></script>
<script src="/static/bootstrap.bundle.min.js"></script>

</html>