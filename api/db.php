<?php
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
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

if (strpos ($_GET ["action"], "|") == -1)
    $actions = [$_GET ["action"]] ;
else
    $actions = explode ("|", $_GET ["action"]) ;

foreach ($actions as $action) {
    switch ($action) {
        default:
            break ;
        case "notify":
            $title = $_SERVER ["HTTP_HOST"];
            $body = $data ['message'];
            $imageUrl = 'https://letsgodil.in/anneli/assets/img/logo.png';

            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
                'image' => $imageUrl,
            ]);

            // $notification = Notification::create($title, $body);

            $sql = "SELECT token from tokens where tokenId = 'notification' and uid = '" . $data ['sender'] ."'";
            $to = sql_exec ($sql, false) [0]["token"];
            // echo ($to) ;
            // var_dump ($to);
            $messaging = $factory->createMessaging();
            $message = CloudMessage::withTarget('token', $to)
                ->withNotification($notification)
                ->withData($data);
            $messaging->send($message);
            break ;
        case "insert":
            if (!isset ($_GET ['table']))
                die () ;
            
            $data ['stamp'] = time ();
            
            db_insert ($_GET ['table'], $data, false);
            echo json_encode ("{code: 1}");
            break ;
        case "update":
            if (!isset ($_GET ['table']))
                die () ;
            
            $data ["update"]['stamp'] = time ();
            $data ["where"]['uid'] = $uid ;
            
            db_update ($_GET ['table'], $data ['update'], $data ['where'], false);
            echo json_encode ("{code: 1}");
            break ;
        case "updatei":
            if (!isset ($_GET ['table']))
                die () ;
            
            $data ["update"]['stamp'] = time ();
            $data ["where"]['uid'] = $uid ;
            
            db_update_or_insert ($_GET ['table'], $data ["update"], $data ["where"], false);
            echo json_encode ("{code: 1}");
            break ;

    }
}
?>