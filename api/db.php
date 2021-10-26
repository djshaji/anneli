<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$_GET ["quiet"] = true ;
chdir ("/var/www/". explode (".",$_SERVER ["HTTP_HOST"])[0]);
include "config.php";
include "anneli/db.php";
include "anneli/token.php";

token_login ();
if ($uid == null) {
    die ("
    {
        code: 0,
        error: 'Unauthorized'
    }
    ");
}

$json = file_get_contents('php://input');
$data = $_POST;
if ($data == null) {
    die ("No data provided") ;
}

switch ($_GET ['action']) {
    default:
        break ;
    case "insert":
        if (!isset ($_GET ['table']))
            die () ;
        
        $data ['stamp'] = time ();
        
        db_insert ($_GET ['table'], $data, false);
        echo json_encode ("{code: 1}");
        break ;
}

?>