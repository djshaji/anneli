<?php
$filename = file_get_contents ("webfonts.json");
$json = json_decode ($filename, true);
$fonts = [] ;
$count = 0 ;
echo "const fonts = ";
foreach ($json ['items'] as $j) {
    array_push ($fonts, $j ['family']);
    // var_dump ($j);
    // die ();
    if ($count > 99)
        break ;
    $count ++;
}
echo json_encode ($fonts);
?>