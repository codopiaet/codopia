<?php
define("UPLOAD_PATH", realpath("./upload/uploads"));

function getPrettyFile($bulkyfile){
    $bulkyfileArr= explode('--',$bulkyfile);
    return $bulkyfileArr[0] . $bulkyfileArr[2];
}
if (isset($_GET["file"])) {
    $file = $_GET["file"];
    if (file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . $file)) {
        // Set headers for file download
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename(getPrettyFile($file)) . "");
        header("Expires: 0");
        header("Cache-Control: must revalidate");
        header("Pragma: public");
        header("Content-Length: " . filesize($file));

        // Read the file and output it to the browser
        readfile($file);
        exit;
    } else die("File not found ". UPLOAD_PATH . DIRECTORY_SEPARATOR . $file);
} else die("Invalid request");

?>