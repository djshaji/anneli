<?php
include "colors.php";
$primary = '#2196f3';
$info = "#9c27b0";
$secondary = "#fff" ;
$skin = "materia" ;
/* COLORS 
PRIMARY
PRIMARY-GRADIENT-1
PRIMARY-GRADIENT-2
PRIMARY-HOVER-1
PRIMARY-HOVER-2
INFO
INFO-GRADIENT-1
INFO-GRADIENT-2
INFO-HOVER-1
INFO-HOVER-2
*/

function hex_to_rgb ($color) {
    // return array_map(
    //     function ($c) {
    //       return hexdec(str_pad($c, 2, $c));
    //     },
    //     str_split(ltrim($colorName, '#'), strlen($colorName) > 4 ? 2 : 1)
    //   );
    $split = str_split(substr ($color, 1), 2);
    $r = hexdec($split[0]);
    $g = hexdec($split[1]);
    $b = hexdec($split[2]);
    return "$r, $g, $b";
}

// Nunito
header('Content-Type: text/css');
if (! isset ($_GET ['font']))
    $font = null ;
else
    $font = $_GET ['font'];
if ($font == '')
    $font = "Montserrat";
if (! isset ($_GET ['theme']))
    $colors = "default";
else
    $colors = $_GET ['theme'];
if ($colors == '')
    $colors = "blue_orange";

if ($_GET ['skin'] != null)
    $skin = $_GET ['skin'];

$theme = sprintf ("@import url('https://fonts.googleapis.com/css2?family=%s&display=swap');", str_replace (" ", "+", $font));
$theme = file_get_contents ("assets/css/material.css");
if ($font != null)
    $theme = str_replace ("Roboto", $font, $theme) ;
if ($colors != null && $colors != "default") {
    $c = get_colors ($colors) ;
    $theme = str_replace ("33,150,243", $c ["primary"], $theme);
    $theme = str_replace ("255,82,82", $c ["info"], $theme);
    // $theme = str_replace ("rgba(33,150,243)", $c ["primary"], $theme);
    // $theme = str_replace ("rgba(255,82,82)", $c ["info"], $theme);
}
echo $theme;
?>