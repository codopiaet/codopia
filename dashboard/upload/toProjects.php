<?php

declare(strict_types=1);
date_default_timezone_set("UTC");

define("UPLOAD_DIRECTORY", $_SERVER["DOCUMENT_ROOT"] . "/dashboard/upload/uploads/");
define("MAXSIZE", 5242880);
define("ALLOWED_EXTENSION", "zip");
define("ALLOWED_MIME", "application/zip");

require_once "../../admin/scripts/classes.php";
require_once "../../admin/scripts/validation.php";
session_start();

$userid = (int)$_SESSION["userid"];
$db = new DataBase();
$xpSystem = new XPSystem($userid);

$last_upload = $db->executeSql("SELECT project_time FROM projects WHERE user_id = ? ORDER BY project_time DESC LIMIT 1", [$userid], true);
if ($last_upload["rows"] != 0) {
    $last_upload = $last_upload[0]["project_time"];
    if (time() - $last_upload < 86400) {
        echo json_encode(["ERROR_CODE" => 1001, "ERROR_MESSAGE" => "You have uploaded a project in the last 24 hours"]);
        exit;
    }
}

function validateFileType(string $tempFilePath, string $extension)
{
    $validFileMime = mime_content_type($tempFilePath);
    $validFileExtension = $validFileMime == $extension;
    return $validFileExtension;
}

function validateFilename($filename)
{
    if (str_contains($filename, "--") or str_contains($filename, "%UzX=") or str_contains($filename, "%PzX=")) {
        return false;
    }
    return true;
}

function handleUpload($formData)
{
    global $userid, $db, $xpSystem;

    $file_name = $formData["theFile"]["name"];
    $project_name = $_POST["projName"];
    $project_desc = $_POST["capInput"];

    // FIXME: CHECK THIS VALIDATION
    if (!(validateString($project_name) && validateStringWithSpaces($project_desc))) {
        echo json_encode(["ERROR_CODE" => 1002, "ERROR_MESSAGE" => "Please fill in all fields."]);
        exit;
    }

    if (empty($file_name) && empty($project_name) && empty($project_desc)) {
        echo json_encode(["ERROR_CODE" => 1002, "ERROR_MESSAGE" => "Please fill in all fields."]);
        exit;
    }

    $tempFilePath = $formData["theFile"]["tmp_name"];
    $isUploadedFile = is_uploaded_file($tempFilePath);
    $validSize = $formData["theFile"]["size"] <= MAXSIZE and $formData["theFile"]["size"] >= 0;

    $pathInfo = pathinfo($file_name); // If the file's name from $_FILES is text_doc.zip,
    $actualFilename = $pathInfo["filename"]; // = text_doc
    $extension = $pathInfo["extension"]; // = zip

    if ($isUploadedFile and $validSize and validateFileType($tempFilePath, ALLOWED_MIME)) {
        // MARK: INSERT FILE TO DATABASE
        $currentTimestamp = time();

        if (!validateFilename($actualFilename)) {
            echo json_encode(["ERROR_CODE" => 1003, "ERROR_MESSAGE" => "Filename cannot contain --"]);
            exit;
        }

        // Insert the path name without project id
        $oldPath = (string)$actualFilename . "--" . $currentTimestamp . "%UzX=" . $userid . "%UzX=" . "--." . $extension; // test_doc--20240420110530%UzX=2%UzX=--.zip // Without project id
        $db->executeSql("INSERT INTO projects (project_name, file_path, project_detail, project_time, user_id) VALUES(?, ?, ?, ?, ?)", [$project_name, $oldPath, $project_desc, $currentTimestamp, $userid]);

        // Select from projects table and get the project id to set it to the file path
        $resultId = (int)$db->executeSql("SELECT project_id FROM projects WHERE file_path = ?", [$oldPath], true)[0]["project_id"];
        $newPath = (string)$actualFilename . "--" . $currentTimestamp . "%UzX=" . $userid . "%UzX=" . "%PzX=" . $resultId . "%PzX=" . "--." . $extension; // test_doc--20240420110530%UzX=2%UzX=%PzX=10%PzX=--.zip // With project id
        $db->executeSql("UPDATE projects SET file_path = ? WHERE project_id = ?", [$newPath, $resultId]);

        $success = move_uploaded_file($tempFilePath, UPLOAD_DIRECTORY . $newPath);

        if (!$success) {
            echo json_encode(["ERROR_CODE" => 1101, "ERROR_MESSAGE" => "An unexpected error occurred; the file could not be uploaded."]);
            exit;
        }

        $xpSystem->addXP(10);
        $db->executeSql("UPDATE users SET uploads = uploads + 1 WHERE id = ?", [$userid]);

        echo json_encode(["SUCCESS" => "File uploaded successfully!"]);
        $_SESSION["last_upload"] = (int) time();
        exit;
    } else {
        echo json_encode(["ERROR_CODE" => 1102, "ERROR_MESSAGE" => "The file you tried to upload is not a valid file. Check file type and size."]);
        exit;
    }
}

// Flow starts here.

$validFormSubmission = !empty($_FILES);

if ($validFormSubmission) {
    $error = $_FILES["theFile"]["error"];

    switch ($error) {
        case UPLOAD_ERR_OK:
            handleUpload($_FILES);
            break;
        case UPLOAD_ERR_INI_SIZE:
            echo json_encode(["ERROR_CODE" => 1103, "ERROR_MESSAGE" => "File size is bigger than allowed."]);
            break;
        case UPLOAD_ERR_PARTIAL:
            echo json_encode(["ERROR_CODE" => 1104, "ERROR_MESSAGE" => "File was only partially uploaded."]);
            break;
        case UPLOAD_ERR_NO_FILE:
            echo json_encode(["ERROR_CODE" => 1105, "ERROR_MESSAGE" => "No file could have been uploaded."]);
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo json_encode(["ERROR_CODE" => 1106, "ERROR_MESSAGE" => "No temp directory! Contact the administrator."]);
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo json_encode(["ERROR_CODE" => 1107, "ERROR_MESSAGE" => "It was not possible to write in the disk. Contact the administrator."]);
            break;
        case UPLOAD_ERR_EXTENSION:
            echo json_encode(["ERROR_CODE" => 1108, "ERROR_MESSAGE" => "A PHP extension stopped the upload. Contact the administrator."]);
            break;
        default:
            echo json_encode(["ERROR_CODE" => 1109, "ERROR_MESSAGE" => "An unexpected error occurred; the file could not be uploaded."]);
    }
} else {
    echo json_encode(["ERROR_CODE" => 1110, "ERROR_MESSAGE" => "The form was not submitted correctly - did you try to access the action url directly?"]);
}
