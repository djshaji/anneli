<?php
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$_GET ["quiet"] = true ;
// var_dump ($_SERVER);

# the following did not parse correctly for subdomains (obviously)
// chdir ("/var/www/". explode (".",$_SERVER ["HTTP_HOST"])[0]);
# hence this
chdir ("/var/www/".pathinfo ($_SERVER ['HTTP_HOST'], PATHINFO_FILENAME));
include "config.php";
include "anneli/db.php";
include "anneli/token.php";
include "anneli/script.php";

token_login ();
if ($uid == null) {
    die ("
    {
        code: auth/nologin,
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
    $target_file = sprintf ("%s/%s/%s___%s",
        $config ["filesdir"], $uid,$v ["name"],sha1 (time ())) ;
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
        $data [$f][$v ["name"]] = basename ($target_file);
      }
}      

// var_dump ($data ["__script__"]);
if (data ["__script__"]) {
    $script = $data ["__script__"] ;
    unset ($data ["__script__"]);
    
    foreach (explode ("|", $script) as $cmd) {
        foreach ($data as $__param => $__value ) {
            if ($cmd [0] == "$") {
                $_cmd = substr ($cmd, 1);
                // echo $_cmd . " " . $__param . "\n" ;
                if ($__param == $_cmd) {
                    // echo "--| replace $cmd with $__value in $script |--\n";
                    if (gettype ($__value) == "array")
                        $script = str_replace ($cmd, json_encode ($__value), $script);
                    else
                        $script = str_replace ($cmd, $__value, $script);
                }
            }
        }
    }

    // var_dump ($script);
    script_run ($script);
}

if ($_GET ["mode"] == "json") {
    if ($_GET ['action'] == "update" || $_GET ['action'] == "updatei" ) unset ($data ["where"]);
    $data = array (
        "data"=> json_encode ($data),
        "module"=> $data ["module"]
        // "__script__"=> $data ["__script__"]
    );    

    if ($_GET ['action'] == "update"||$_GET ['action'] == "updatei" ) {
        $data = array (
            "update" => $data,
            "where" => json_decode ($_POST ['where'], true)
        );
    }
} 

// var_dump ($data);
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
            $imageUrl = $config ["logo"];

            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
                'image' => $imageUrl,
            ]);

            // $notification = Notification::create($title, $body);
            $xdata = array () ;
            foreach ($data as $d => $v) {
                if (gettype ($v) == "array") {
                    $xdata [$d] = json_encode ($v) ;
                } else #if (gettype ($v) == "string")
                    $xdata [$d] = $v ;
            }
                
            $sql = "SELECT token from tokens where tokenId = 'notification' and uid = '" . $data ['sender'] ."'";
            $to = sql_exec ($sql, false) [0]["token"];
            if ($to == null)
                return ;
            // echo ($to) ;
            // var_dump ($data);
            $xdata ["uid"] = $uid ;
            $messaging = $factory->createMessaging();
            $message = CloudMessage::withTarget('token', $to)
                ->withNotification($notification)
                ->withData($xdata);
            $messaging->send($message);
            break ;
        case "insert":
            if (!isset ($_GET ['table']))
                die () ;
            
            $data ['stamp'] = time ();
            $xdata = array () ;
            foreach ($data as $d => $v) {
                if (gettype ($v) == "array") {
                    $xdata [$d] = json_encode ($v) ;
                } else #if (gettype ($v) == "string")
                    $xdata [$d] = $v ;
            }
                
            
            db_insert ($_GET ['table'], $xdata, false);
            echo json_encode ("{code: 1}");
            break ;
        case "update":
            if (!isset ($_GET ['table']))
                die () ;
            
            $data ["update"]['stamp'] = time ();
            $data ["where"]['uid'] = $uid ;
            // var_dump ($data);
            // db_update ($_GET ['table'], $data ['update'], $data ['where'], false);
            db_update_multi ($_GET ['table'], $data ['update'], $data ['where'], false);
            echo json_encode ("{code: 1}");
            break ;
        case "updatei":
            if (!isset ($_GET ['table']))
                die () ;
            
            $data ["update"]['stamp'] = time ();
            $data ["where"]['uid'] = $uid ;
            
            // var_dump ($data);
            db_update_or_insert ($_GET ['table'], $data ["update"], $data ["where"], false);
            echo json_encode ("{code: 1}");
            break ;
        case "delete":
            // Ok so this needs to be very safe
            if (!isset ($_GET ['table']) || ! isset ($data ["auto_id"]))
                die () ;

            db_delete ($_GET ["table"], $data ["auto_id"], false);
            echo json_encode ("{code: 1}");
            break ;

    }
}
?>