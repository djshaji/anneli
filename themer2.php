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
$theme = file_get_contents ("assets/css/themes/". $skin ."/bootstrap.css");
if ($font != null)
    $theme = str_replace ("bs-font-sans-serif: ", "bs-font-sans-serif: ". $font . ',', $theme) ;
if ($colors != null && $colors != "default") {
    $c = get_colors ($colors) ;

    $PRIMARY = explode (";",explode ( "--bs-primary: ", $theme) [1])[0];

    foreach (['primary', 'info'] as $accent) {
        $theme = str_replace (
            explode (";",explode ( "--bs-" . $accent .": ", $theme) [1])[0],
            $c [$accent], 
            $theme
        ) ;
    }

    /*
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
    */
    // $theme = str_replace ($info, $c ['info'], $theme) ;
}
echo $theme;
?>