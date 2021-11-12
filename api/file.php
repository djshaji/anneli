<?php
// ini_set('display_errors', 1);
chdir ("/var/www/". explode (".",$_SERVER ["HTTP_HOST"])[0]);
include "config.php";
include "anneli/token.php";
token_login ();

if ($uid == null) {
    header("HTTP/1.0 403 Forbidden");

    // Let the client know that the output is JSON
    header('Content-Type: application/json');

    // Output the JSON
    echo json_encode(array(
        'ErrorCode'    => 403,
        'ErrorMessage' => 'Not Authorized',
    ));
    // Always terminate the script as soon as possible
    // when setting error headers
    die;
}

if ($_GET ["user"])
    $filename = $config ["filesdir"] . '/'. $_GET ["user"] . "/" . $_GET ["file"] ;
else
    $filename = $config ["filesdir"] . '/'. $uid . "/" . $_GET ["file"] ;
// echo $filename ;

// header('X-Sendfile', $filename);
// header('Content-Type' , 'application/octet-stream');
// header('Content-Disposition', 'attachment; filename="' . basename ($filename) . '"');
switch ($_GET ["action"]) {
    case "open":
    default:
        if (file_exists($filename)) {
            $basename = explode ("___", basename ($filename)) [0];
            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
            // header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($filename));
            header("Content-Disposition: attachment; filename=$basename");
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            readfile($filename);
            die();        
        } else {
            die("Error: File not found.");
        } 
        break ;
    case "delete":
        unlink ($filename);
        die ("{code:1}");
        break ;
}
?>