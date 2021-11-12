<?php

function setperm ($array) {
    /*  setperm
        usage: setperm file uid permission
        the first parameter is the function name
    */
    // echo "DD";
    foreach (json_decode ($array [1], true) as $file => $filename) {
        $entry = array (
            "user" => $array [2],
            "filename" => $filename,
            "permission" => "read",
            "stamp" => time ()
        );

        // var_dump ($entry);
        db_insert ("filepermissions", $entry, false) ;
    }
}

$functions = [
    "setperm"
] ;

function script_run ($script) {
    global $functions ;
    $lines = explode ("\n", $script) ;
    foreach ($lines as $line) {
        $cmd = explode (" ", $line) ;
        // var_dump ($functions);
        // echo $cmd [0] . "\n";
        // var_dump (in_array ($cmd [0], $functions));
        if (in_array ($cmd [0], $functions))
            call_user_func ($cmd [0], $cmd);
    }    
}

?>