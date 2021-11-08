<?php
function get_fee_structure ($csv) {
    // $csv = $_GET ["sem"] ;
    if ($csv == null)
        $csv = "ug1.csv" ;
    // else
    //     $csv .= '.csv';
    $myfile = fopen($csv, "r") or die("Unable to open file!");
    $contents = fread($myfile,filesize($csv));
    fclose($myfile);

    $parse = explode ("\n", $contents);
    $headers = explode ( ',', $parse [0]) ;
    unset ($headers [0]);
    $fee_structure = array () ;
    for ($i = 1 ; $i < sizeof ($parse) ; $i ++) {
        $split = explode ("," , $parse [$i]);

        $comb =  '' ;
        if (strpos ($split [0], "(") != false)
            $comb = explode ("(", $split [0])[1] ;
        if (strpos ($comb, ")") != false)
            $comb = explode (")", $comb) [0];
        // if (strpos ($split [0], "BBA") == false)
            // echo 'found ' ,explode (")", explode ("(", $split [0])[1]) [0] , "\n" ;

        if  (strpos ($split [0], "BBA"))
            $comb = "BBA" ;
        if  (strpos ($split [0], "BCA"))
            $comb = "BCA" ;
        if  (strpos ($split [0], "BCOM"))
            $comb = "BCOM" ;

        unset ($split [0]);
        $fee_structure [$comb] = $split ;
    }

    return [$headers, $fee_structure];
}

// foreach (get_fee_structure(null) as $r)
// foreach ($r as $a => $f    )
//     echo $a . "\n";
// $fee = get_fee_structure ('ug5.csv');
// foreach ($fee [1] as $f => $v)
//     echo $f . "\n";
// var_dump ($fee [1] ["BBA"][2])
// echo explode (")", explode ("(", "(BBA)") [1])[0];

function parse_csv_file ($filename, $col = 4, $delimiter = ',', $newline = "\n", $replace_with = "") {
    $myfile = fopen($filename, "r") or  var_dump (error_get_last ());
    // $contents = fread($myfile,filesize($filename));
    $contents = '';
    while (!feof ($myfile))
        $contents .= fread ($myfile, 8192);
    fclose($myfile);

    $contents = str_replace (", ", $replace_with, $contents);
    $parse = explode ($newline, $contents);
    $headers = explode ($delimiter , $parse [0]) ;
    unset ($parse [0]);

    $res = array ();
    foreach ($parse as $p) {
        $array = explode ($delimiter, $p) ;
        $m = trim ($array [$col] );
        $res [$m] = array () ;
        for ($i = 0 ; $i < sizeof ($headers)  ; $i ++) {
            $res [$m][$headers [$i]] = $array [$i];
        }
    }
  
    return $res ;
}

// var_dump (parse_csv_file ("sem1-online-classes.csv", 0) ["English"]);
?>