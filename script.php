<?php

function setperm ($array) {
    /*  setperm
        usage: setperm|file|uid|permission|type
        the first parameter is the function name
    */
    // echo "DD";
    var_dump ($array [1]);
    foreach (json_decode ($array [1], true) as $file => $filename) {
        $entry = array (
            "user" => $array [2],
            "filename" => $filename,
            "permission" => $array [3],
            "type" => $array [4],
            "stamp" => time ()
        );

        if (! isset ($entry ["permission"]) || $entry ["permission"] == null) {
            $entry ["permission"] = "read" ;
        }
        if (! isset ($entry ["type"]) || $entry ["type"] == null) {
            $entry ["type"] = "user" ;
        }
        // var_dump ($entry);
        db_insert ("filepermissions", $entry, false) ;
    }
}

$functions = [
    "setperm"
] ;

function script_run ($script) {
    // var_dump ($script);
    global $functions ;
    $lines = explode ("\n", $script) ;
    foreach ($lines as $line) {
        $cmd = explode ("|", $line) ;
        // var_dump ($functions);
        // echo $cmd [0] . "\n";
        // var_dump (in_array ($cmd [0], $functions));
        if (in_array ($cmd [0], $functions))
            call_user_func ($cmd [0], $cmd);
    }    
}

?>