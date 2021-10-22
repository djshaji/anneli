<?php
require __DIR__.'/vendor/autoload.php';

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;


function token_verify ($idTokenString, $user = false) {
    $factory = (new Factory)->withServiceAccount('../letsgodil-admin/lets-go-dil-firebase-adminsdk-8j1sk-900215dc14.json');
    $auth = $factory->createAuth();
    $verifiedIdToken = null; 
    try {
        $verifiedIdToken = $auth->verifyIdToken($idTokenString);
    } catch (InvalidArgumentException $e) {
        // echo 'The token could not be parsed: '.$e->getMessage();
        ?>
        <?php
    } catch (InvalidToken $e) {
        echo 'The token is invalid: '.$e->getMessage();
    }
    
    if ($verifiedIdToken == null)
        return null ;
    $uid = $verifiedIdToken->getClaim('sub');
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
    $factory = (new Factory)->withServiceAccount('../plasma-admin/codename-plasma-firebase-adminsdk-89ymr-2cfabe5526.json');
    $auth = $factory->createAuth();
    $user = $auth->getUser($uid);
    return $user ;

}

