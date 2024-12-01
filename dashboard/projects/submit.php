<?php
require_once("../../admin/scripts/validation.php");
require_once ("../../admin/scripts/classes.php");

session_start();

$projName = $_POST["projName"];
$projDesc = $_POST["projDesc"];
$projFile = $_FILES["projFile"];
$projId = $_POST["projId"];

if (!(validateString($projName) && validateStringWithSpaces($projDesc))){
    echo json_encode(["error"=>"Validation error"]);
    exit;
}
// The update stuff! // update at once
$db = new DataBase();


$currentDate = date("m-d-h-m-s");
$fileName =  'uploads/' .'' ."$currentDate" . "UID-{$_SESSION['userid']}";
$file = fopen($fileName, "wb"); 
$fileContent = fread($projFile['tmp_name'], 5*1024*1024); 
fwrite($file, $fileContent); 
fclose($file); 
$db->executeSql("update tables set file_path=? and project_detail=? and project_name=? where project_id=? and user_id=?", [$fileName, $projDesc, $projName, $projId, (int)$_SESSION["userid"]]);
die(json_encode(["success"=>1]));