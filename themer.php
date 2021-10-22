<?php
include "colors.php";
$primary = '#2196f3';
$info = "#9c27b0";
$secondary = "#fff" ;
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
$theme = sprintf ("@import url('https://fonts.googleapis.com/css2?family=%s&display=swap');", str_replace (" ", "+", $font));
$theme = file_get_contents ("assets/css/bootstrap.css");
if ($font != null)
    $theme = str_replace ("Montserrat", $font, $theme) ;
if ($colors != null) {
    $c = get_colors ($colors) ;
    $theme = str_replace ("PRIMARY-HOVER-2", $c ['primary'], $theme) ;
    $theme = str_replace ("PRIMARY-GRADIENT-1", $c ['primary'], $theme) ;
    $theme = str_replace ("PRIMARY-HOVER-1", $c ['primary-dark'], $theme) ;
    $theme = str_replace ("PRIMARY-GRADIENT-2", $c ['primary-dark'], $theme) ;
    $theme = str_replace ("INFO-HOVER-1", $c ['info'], $theme) ;
    $theme = str_replace ("INFO-GRADIENT-2", $c ['info'], $theme) ;
    $theme = str_replace ("INFO-HOVER-2", $c ['info'], $theme) ;
    $theme = str_replace ("INFO-GRADIENT-1", $c ['info'], $theme) ;
    $theme = str_replace ("PRIMARY", $c ['primary'], $theme) ;
    $theme = str_replace ("INFO", $c ['info'], $theme) ;
    // $theme = str_replace ($info, $c ['info'], $theme) ;
}
echo $theme;
?>