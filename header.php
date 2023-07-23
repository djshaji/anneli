<?php
if ($quiet != true && $config ["analytics"] == false ) {
  echo "<script>const analyticsEnabled = false;</script>";
}

$root = [
  "2wdKBu0eqCXATgjS7ilpwEPYj3J3"
];

ini_set('upload_max_filesize', '200M');
date_default_timezone_set('Asia/Kolkata');
include 'anneli/token.php';
define ("GOOGLE_FONTS_API_KEY", "AIzaSyCxoa3sC5zzGbePrKAPXHXsTqdNpg6jceE");
// $build = "αlpha ". exec ("git rev-list HEAD --count");
// $build_date = exec ("git log -1 --format=%cd") ;

// first we parse the config
$theme = $config ["theme"];
$font = $config ["font"];
$skin = $config ["skin"];

if ($_COOKIE ['font'])
  $font = $_COOKIE ['font'];
// if ($theme == null)
if ($_COOKIE ['theme'])
  $theme = $_COOKIE ['theme'];

if ($theme == null) {
  $theme = 'blue_deep_orange';
  // $theme = 'cyan_red';
}
if ($_COOKIE ['skin'])
  $skin = $_COOKIE ['skin'];
if ($skin == null) {
  $skin = 'materia';
  // $theme = 'cyan_red';
}
$categories = [
  "Poetry",
  "Stories",
  "Non Fiction"
];
if (!isset ($_GET ['quiet']))
  echo "<script>theme = '$theme' ; font = '$font'; skin = '$skin';icon_theme = 'Pop';</script>";
else
  $quiet = true ;
if ($_SESSION == null) {
  $token = $_COOKIE ['token'];
  $email = $_COOKIE ['email'];
  if ($token != null) {
    $uid = token_verify ($token, true);
    // var_dump ($uid);
    if ($uid != null) {
      session_start () ;
      $_SESSION ['uid'] = $uid ;
      if (!isset ($_GET ['quiet'])) {
      ?>
      <script>
        uid = "<?php echo $uid ;?>" ;
      </script>
      <?php
      }
      // $_SESSION ['email'] = $uid -> {"email"} ;
    }
  } else {
    session_unset () ;
    if ($_SESSION)  
      session_destroy () ;
  }
}

include 'anneli/functions.php';
// include "anneli/scripts.php";
// $codename = ucwords (explode (".", $_SERVER ["HTTP_HOST"]) [0]) . ' @ GDC Udhampur';
if ($config ['codename'] == null)
  $codename = 'Anneli';
else
  $codename = $config ['codename'];
if ($config ['description'] == null)
  $description = 'Content Management';
else
  $description = $config ['description'];
$module = ucwords (substr (explode ('.',$_SERVER['REQUEST_URI']) [0],1));
if ($module == 'Index' || $module == '')
  $module = 'Home';

if (!isset ($_GET ['quiet'])) {

?>

<!-- To Do: Upgrade to Firebase 9 -->
<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-app-compat.js"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-firestore-compat.js"></script> -->
<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-analytics-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-messaging-compat.js"></script>

<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<!-- <script src="https://www.gstatic.com/firebasejs/7.14.4/firebase-app.js"></script> -->
<!-- Add Firebase products that you want to use -->
<!-- <script src="https://www.gstatic.com/firebasejs/7.14.4/firebase-auth.js"></script> -->
<!-- <script src="https://www.gstatic.com/firebasejs/7.14.4/firebase-firestore.js"></script> -->
<!-- <script src="https://www.gstatic.com/firebasejs/7.14.5/firebase-storage.js"></script> -->
<!-- <script src="https://www.gstatic.com/firebasejs/7.14.5/firebase-analytics.js"></script> -->
<script src="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.js"></script>
<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.0/js/bootstrap.min.js"></script> -->

<!-- for bootstrap 5 -->
<?php if (!isset ($_GET ["printa"])) { ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<?php } if (file_exists (getcwd () . "/favicon.png")) {?>
<link rel="shortcut icon" type="image/jpg" href="/favicon.png"/>
<?php } else { ?>
<link rel="shortcut icon" type="image/jpg" href="anneli/assets/img/favicon.png"/>
<?php } ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<!-- <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.blue-red.min.css" /> -->
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

<!-- New Material Symbols, yay! -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<!-- <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.css"> -->
<?php if (! isset ($_GET ['print'])) {?>
<link type="text/css" rel="stylesheet" href="<?php echo "/anneli/themer_mdl.php?font=$font&theme=$theme&skin=$skin" ;?>">
<link type="text/css" rel="stylesheet" href="<?php echo "/anneli/themer2.php?font=$font&theme=$theme&skin=$skin" ;?>">
<?php }?>
<!-- <link type="text/css" rel="stylesheet" href="/assets/css/themes/green.css"> -->
<!-- <link type="text/css" rel="stylesheet" href="/assets/css/fonts.css"> -->
<!-- <link type="text/css" rel="stylesheet" href="assets/css/font-awesome.min.css"> -->
<link type="text/css" rel="stylesheet" href="anneli/assets/css/all.min.css">
<link type="text/css" rel="stylesheet" href="anneli/assets/css/style.css">
<link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/3.5.2/firebaseui.css" />
<?php if (file_exists ($config ["dir"] . "/" . "firebaseConfig.js")) { ?>
  <script src="/firebaseConfig.js?<?php echo time () ;?>"></script>
<?php } else { ?>
  <?php echo "<script>fconfig = '".$config ["dir"] . "/" . "firebaseConfig.js'</script>";?>
  <script>console.warn ("using default firebase config js | not found:", fconfig)</script>
  <script src="anneli/firebaseConfig.js?<?php echo time () ;?>"></script>
<?php } ?>
<script src="anneli/util.js?<?php echo time () ;?>"></script>
<script src="anneli/mime.js"></script>
<script src="anneli/colors.js?"></script>
<script src="anneli/fonts.js?"></script>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./anneli/assets/img/apple-icon.png">
  <!-- <link rel="icon" type="image/png" href="./anneli/assets/img/favicon.png"> -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta property="og:image" content="https://<?= $_SERVER ["SERVER_NAME"] . $config ["logo"]?>">

  <title>
    <?php echo $codename ;?>

  </title>
  <meta name="description" content="<?php echo $description ;?>">
  <meta name="keywords" content="<?php echo $codename ; echo $description ;?>">
  <meta name="author" content="Shaji Khan">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=1, shrink-to-fit=yes' name='viewport' />
</head>
<body class="index-page sidebar-collapse">
  <!-- Navbar -->
  <div class="mdl-layout mdl-js-layout">
  <?php if (!isset ($_GET ["print"])) { ?>
  <div id="header-navbar" class="navbar navbar-expand-lg navbar-dark bg-<?php echo $config ['header-bg'] .' ' ; if ($config ['header'] == false) echo 'd-none' ;?>">
    <div class="container">
      <div class="d-md-none ms-3"></div>
      <a href="../" class="navbar-brand">
        <!-- <span class="dogra category border p-1 pl-2 pr-2 shadow rounded-circle pb-2">𑠎</span> -->
        <img src="<?php echo $config ["logo"] ;?>" width=50>
        <?php echo $codename ;?>
      </a>
      <!-- <span id="email" class="badge bg-success text-white p-2"></span> -->
  
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <!-- <span class="navbar-toggler-icon"></span> -->
        <i class="fas fa-ellipsis-v"></i>
      </button>
      <div class="justify-content-end collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav" id="navigation">          
          <!-- <li class="nav-item">
            <a class="nav-link" href="/exam.php#instructions">How to give exam</a>
          </li> -->
          <li class="nav-item" >
            <!-- <span id="email" class="badge bg-success text-white p-3"></span> -->
            <div class="spinner-border text-light" role="status" id="login-spinner">
              <span class="sr-only">Loading...</span>
            </div>

            <button class="btn btn-sm ml-2 nav-link btn-primary d-none" data-bs-toggle="modal" data-bs-target="#login" id="menu-login">
              <i class="fa fa-shield-alt"></i> Login
            </button>
            
            <li class="nav-item d-none dropdown" id="menu-account">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php if ($user && $user ->get ("picture")) {?>
                  <img width="24" class="p-1" src="<?php echo $user ->get ("picture") ;?>"/>
                <?php }else {?>
                  <i class="mr-1 fas fa-user-circle"></i>
                <?php }?>
                <span id="email"></span>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php if ($is_faculty) { ?>
                <a class="dropdown-item" href="biodata.php">
                  <i class="fas fa-user-alt"></i>&nbsp;
                  My Biodata
                </a>
                <?php } else { ?>
                <!-- <div class="dropdown-divider"></div> -->
                  <!-- <a class="dropdown-item" href="student.php"><i class="fas fa-edit"></i>&nbsp;&nbsp;Give Exam</a> -->
                <?php } ?>
                <!-- <a class="dropdown-item" href="words.php">My Words</a> -->
                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#colors">
                  <i class="fas fa-cog"></i>&nbsp;Settings
                </button>
                <!-- <div class="dropdown-divider"></div> -->
                <a class="dropdown-item" href="javascript:logout ()">
                  <i class="fa fa-shield-alt"></i>&nbsp;Logout
                </a>
              </div>
            </li>

            <!-- <a class="btn btn-outline-primary text-white d-none" href="javascript:logout ()" id="menu-account">
              <i class="fas fa-user-circle"></i> Profile
            </a>
            <a class="btn btn-danger d-none" href="javascript:logout ()" id="menu-logout">
              <i class="fa fa-shield-alt"></i> Logout
            </a> -->

          </li>

        </ul>
      </div>
    </div>
  </div>
  <!-- End Navbar -->

  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title">Menu</span>
    <nav class="mdl-navigation">
      <?php
        foreach ($config ["drawer-auth"] as $name => $link) {
          echo "<a class='mdl-navigation__link' href='$link'>$name</a>";
        }
        foreach ($config ["drawer"] as $name => $link) {
          echo "<a class='mdl-navigation__link' href='$link'>$name</a>";
        }
      ?>
    </nav>
  </div>
  <?php } ?>


<?php 
} 
if (! $quiet) {
?>


<?php } ?>
<style>
.mdl-layout__drawer-button {
  color: white;
}
</style>