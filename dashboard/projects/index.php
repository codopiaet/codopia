<?php
include("../../admin/scripts/dashboard_setup.php");

session_start();

if (!$_SESSION["loggedin"]) {
  header("Location: /login");
  exit;
}

$statementForProjectFetching = $con->prepare("select * from projects where user_id=?");
$statementForProjectFetching->execute([$_SESSION["userid"]]);
$statementForProjectFetching->setFetchMode(PDO::FETCH_ASSOC);
$projectList = $statementForProjectFetching->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/imgs/logo.png">
  <link rel="stylesheet" href="project.css">
  <link rel="stylesheet" href="../side.css">
  <link rel="stylesheet" href="../loader.css">
  <link rel="stylesheet" href="/static/bootstrap.min.css">
  <link rel="stylesheet" href="/static/bootstrap.min.css">
  <link rel="stylesheet" href="/static/sweetalert2.min.css">
  <title>Projects - <?php echo $_SESSION["username"] ?></title>
  <style>
    .test-one {
      height: 2px;
    }
  </style>
</head>

<body>

  <div id="loader-container">
    <div class="loader"></div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div id="sidebar" class="sidebar d-none d-md-block col-md-3 sidebar-g position-relative shadow" style="background-color: #1e2d40;">
        <div class="logo d-flex h-25 justify-content-center align-items-center">
          <img src="/imgs/text-logo.png" style="width: 187.5px;" alt="codopia logo">
        </div>
        <ul class="nav mt-5 flex-column">
          <li class="nav-item">
            <a class="nav-link" href="../">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
              </svg> Home</a>
          </li>
          <li class="nav-item">
            <a href="/test_page/" class="nav-link"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-code-slash" viewBox="0 0 16 16">
                <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0" />
              </svg> Test</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-buildings" viewBox="0 0 16 16">
                <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z" />
                <path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z" />
              </svg> Projects</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../upload/">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-upload" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
              </svg> Upload</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../feedback/">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi me-2 bi-journal-arrow-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 11a.5.5 0 0 0 .5-.5V6.707l1.146 1.147a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 .5.5" />
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
              </svg> Feedback</a>
          </li>
        </ul>
      </div>

      <!-- Mobile view navigation -->
      <div id="mob-view" class="navbar navbar-expand-md navbar-light bg-light d-md-none shadow" style="z-index: 999;">
        <ul class="mt-3 p-0" id="mv-ul">
          <li class="nav-item">
            <a href="../" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 bi bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
              </svg></a>
            <!-- HOME -->
          </li>

          <li>
            <a href="/test_page/" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi me-2 bi-balloon" viewBox="0 0 16 16">
                <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0" />
              </svg></a>
            <!-- TEST -->
          </li>

          <li>
            <a href="#" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 bi bi-buildings" viewBox="0 0 16 16">
                <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z" />
                <path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z" />
              </svg></a>
            <!-- PROJECTS -->
          </li>

          <li>
            <a href="../upload/" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 bi bi-upload" viewBox="0 0 16 16">
                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
              </svg></a>
            <!-- UPLOADS -->
          </li>

          <li>
            <a href="../feedback/" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-journal-bookmark me-2" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8" />
                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
              </svg></a>
            <!-- FEEDBACKS -->
          </li>
        </ul>
      </div>

      <!-- Main content container -->
      <div class="container col-md-9 main-content-g position-relative">
        <div class="container mt-4">
          <div class="table-responsive">
            <table class="table table-hover table-striped" id="project_table">
              <thead>
                <?php
                echo "<tr>";
                echo "  <th>Project Name</th>";
                echo "  <th>Caption</th>";
                echo "  <th>Total Likes</th>";
                echo "  <th>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($projectList as $project) {
                  $projName = $project["project_name"];
                  $projDesc = $project["project_detail"];
                  $projectId = $project["project_id"];
                  $likes = $project["likes"];

                  echo "<tr>";
                  echo "  <td>$projName</td>";
                  echo "  <td>$projDesc</td>";
                  echo "  <td>$likes</td>";
                  echo "  <td><button projectId='{$project['project_id']}' class='btn btn-danger p-1 delete-btn'>Delete</button> 
                  <button projectId='{$project['project_id']}' class='btn btn-primary pl-3 edit-btn'>Edit</button></td>";
                  echo "</tr>";
                }
                ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../loader.js"></script>
  <script src="/static/sweetalert2.js"></script>
  <script src="projects.js"></script>
  <script src="delete.js"></script>
  <script>
    const editButtons = document.querySelectorAll(".edit-btn");
    let projectId;
    let projectName;
    let ProjectDesc;
    editButtons.forEach(button => {
      button.addEventListener("click", ev => {
        ev.preventDefault();
        Swal.fire({
          title: "Enter your edited values",
          html: `
          <div class="form-group">
          <input class="form-control" id="newname" placeholder="Name" required>
          <input class="form-control mt-3" id="newdesc" placeholder="Description">
          </div>
          `,
          showCancelButton: true,
          preConfirm: async () => {
            projectName = document.getElementById("newname").value
            ProjectDesc = document.getElementById("newdesc").value
            projectId = ev.target.getAttribute("projectId")
            const formData = new FormData();
            formData.append("name", projectName)
            formData.append("projId", projectId)
            formData.append("projDesc", ProjectDesc)
            let resp = await fetch("edit.php", {
              method: "POST",
              body: formData
            }).then(resp => resp.json())
            if (resp.error) {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: resp.error
              })
            }
            if (resp.success) {
              // FIXME: added the following condition
              if (projectName != "") ev.target.parentElement.parentElement.children[0].textContent = projectName;
              if (ProjectDesc != "") ev.target.parentElement.parentElement.children[1].textContent = ProjectDesc;
            }

          }
        })
      })
    })
  </script>
</body>

</html>