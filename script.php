<?php

function setperm ($array) {
    /*  setperm
        usage: setperm file uid permission
        the first parameter is the function name
    */
    $entry = array (
        "user" => $array [2],
        "filename" => $array [1],
        "permission" => "read"
    );

    db_insert ("filepermissions", $entry, false) ;
}

$functions = [
    "setperm"
] ;

function script_run ($script) {
    $lines = explode ("\n", $array) ;
    foreach ($lines as $line) {
        $cmd = explode (" ", $line) ;
        if (isset ($functions [$cmd [0]]))
            $functions [$cmd [0]] ($cmd);
    }    
}

?>