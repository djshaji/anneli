<?php
require __DIR__.'/vendor/autoload.php';

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;

$auth = null ;
$user = null ;
//var_dump ($config ["serviceAccount"]);
function token_verify ($idTokenString, $user = false) {
    global $auth, $factory, $config, $user ;
    $factory = (new Factory)->withServiceAccount($config ["serviceAccount"]);
    $auth = $factory->createAuth();
    if ($auth == null) {
        dir ("cannot create auth");
    }
    $verifiedIdToken = null; 
    try {
        $verifiedIdToken = $auth->verifyIdToken($idTokenString);
    } catch (InvalidArgumentException $e) {
        if ($verifiedIdToken == null) {
            echo '<script>console.log ("The token could not be parsed: '.$e->getMessage() . '")</script>';
            // var_dump ($e);
        }
        ?>
        <?php
    } catch (InvalidToken $e) {
        echo 'The token is invalid: '.$e->getMessage();
    } catch (Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
        // echo '<script>console.log ("Token not verified: ")</script>';

    }
    
    if ($verifiedIdToken == null)
        return null ;
    $user = $verifiedIdToken->claims('sub');
    // var_dump ($user ->get ("user_id"));
    // $user = $user [0];
    $uid = $user ->get ("user_id");
    $email = $user ->get("email");
    // $user = $auth->getUser($uid);

    // var_dump ($user -> {"uid"}) ;
    // var_dump ($user -> {"email"}) ;
    // if (!$user)
    return $uid ;
    // else
    //     return $auth->getUser($uid);
}

// var_dump (token_verify ($argv [1]));

function token_get_user ($uid) {
    global $config ;
    $factory = (new Factory)->withServiceAccount($config ["serviceAccount"]);
    $auth = $factory->createAuth();
    $user = $auth->getUser($uid);
    return $user ;

}

function token_login () {
    global $uid, $email ;
    if ($_SESSION == null) {
        $token = $_COOKIE ['token'];
        $email = $_COOKIE ['email'];
        if ($token != null) {
          $uid = token_verify ($token, true);
          if ($uid != null) {
            session_start () ;
            $_SESSION ['uid'] = $uid ;
            if (!isset ($_GET ['quiet'])) {
            ?>
            <?php
            }
          }
        } else {
          session_unset () ;
          if ($_SESSION)  
            session_destroy () ;
        }
      }    
}