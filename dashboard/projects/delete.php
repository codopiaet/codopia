<?php
declare(strict_types=1);
define("UPLOAD_DIR", realpath("/dashboard/upload/uploads"));
require_once "../../admin/scripts/classes.php";
session_start();
$userid = (int)$_SESSION["userid"];

$db = new DataBase();
$xp = new XPSystem($userid);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = (int) file_get_contents("php://input");
    $result = $db->executeSql("SELECT * FROM projects WHERE project_id = ?", [$data], true)[0];
    $path = "../upload/uploads/" . $result["file_path"];
    $path = realpath($path);
    if (unlink($path)) {
        $db->executeSql("DELETE FROM projects WHERE project_id = ?", [$data]);
        $db->executeSql("UPDATE users SET uploads = uploads - 1 WHERE id = ?", [$userid]);
        $xp->decreaseXP(10);
        die(json_encode(["CODE" => 2001, "MESSAGE" => "Deleted"]));
    } else {
        die(json_encode(["CODE" => 2002, "MESSAGE" => "Unexpected error occurred; Failed to delete project."]));
    }
}

?>