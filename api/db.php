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

// $json = file_get_contents('php://input');
// var_dump ($_FILES);
// var_dump ($_POST);
$data = $_POST;
if ($data == null) {
    die ("No data provided") ;
}

foreach ($_FILES as $f => $v) {
    // var_dump ($_FILES);
    $target_file = sprintf ("../anneli-files/%s/%s___%s",
        $uid,$v ["name"],sha1 (time ())) ;
    mkdir (dirname ($target_file), 0777, true);
    if ($data [$f] == null) $data [$f] = array ();
    // var_dump (error_get_last ());
    if (! move_uploaded_file($_FILES[$f]["tmp_name"], $target_file) || $_FILES[$f]["error"] || ! file_exists ($target_file)) {
        printf ('<script>
        Swal.fire ("File not uploaded", "The file could not be uploaded. Try again.<br>%s", "error").then((e)=>{ 
        location.href = "%s"
        })

        </script>',  $_FILES[$f]["error"], $_SERVER['HTTP_REFERER']);
        die () ;
      } else {
        $data [$f][$v ["name"]] = $target_file;
      }
}      

if ($_GET ["mode"] == "json") {
    if ($_GET ['action'] == "update") unset ($data ["where"]);
    $data = array (
        "data"=> json_encode ($data),
        "module"=> $data ["module"]
    );    

    if ($_GET ['action'] == "update") {
        $data = array (
            "update" => $data,
            "where" => json_decode ($_POST ['where'], true)
        );
    }
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
            if ($to == null)
                return ;
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
            
            // db_update ($_GET ['table'], $data ['update'], $data ['where'], false);
            db_update_multi ($_GET ['table'], $data ['update'], $data ['where'], false);
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