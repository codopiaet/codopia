<?php

require_once("../../admin/scripts/classes.php");
session_start();

if (!$_SESSION["loggedin"]){
    header("Location: /login");
    exit;
}
$subid = $_GET["subid"];

if (!isset($subid)) {
    header("Location: ..");
    exit;
}
$subjectMap = [1=>"JavaScript",2=>"HTML"];
switch ($subid) {
    case "JavaScript":
        define("SUBJECT_ID",1);
        break;
    case "HTML":
        define("SUBJECT_ID",2);
        break;
    default:
        header("Location: ..");
        exit; 
}

$db = new DataBase();
$subjectCheck = $db->executeSql("select taken_by from test_list where name=?", [$subjectMap[SUBJECT_ID]], true);

$checker = (array)json_decode($subjectCheck[0]["taken_by"]);
if (in_array(SUBJECT_ID,$checker)){
    die("You have already taken this test");
}
if (!isset($_SESSION["doneTests"])){
    $_SESSION["doneTests"] = [];
}

/////// First try to put the subject_ID in the Cache
$cache = new Cache($_SESSION["userid"]);
$cache->set("1", SUBJECT_ID);
$subjectMap = [1=>"JavaScript",2=>"HTML"];
////


$questionOne = $db->executeParams("select question,a,b,c,d from tests where subject=? limit 1 offset ?",[$subjectMap[SUBJECT_ID], (int)$cache->get() -1],TRUE);

$question = $questionOne[0]["question"];
$choices = [
    "a"=>$questionOne[0]["a"],
    "b"=>$questionOne[0]["b"],
    "c"=>$questionOne[0]["c"],
    "d"=>$questionOne[0]["d"]
];
$topic = $subjectMap[SUBJECT_ID];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Test - Math</title>
</head>
<body>
    <div class="main">
    <div id="main-body"></div>
    </div>
    <form action="d.php" method="post" id="form">
    <div class="container">
        <h4 id="topic"><?php echo $topic?></h4>
        <h4 id="qn" ><?php echo $question ?></h4>
    <a class="choices" name="a" id="a">A. <?php echo $choices["a"]; ?></a>
    <a class="choices" name="b" id="b">B. <?php echo $choices["b"];  ?></a>
    <a class="choices" name="c" id="c">C. <?php echo $choices["c"];  ?></a>
    <a class="choices" name="d" id="d">D. <?php echo $choices["d"];  ?></a>
    <button id="submitter" type="submit">Submit</button>
    <div class="error" id="error"></div>
</form>
    </div>
<form id="a" action="d.php"></form>
    <script src="script.js"></script>
    <script src="purify.min.js"></script>
    <script>
    </script>
</body>
</html>
