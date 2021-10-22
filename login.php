<?php
include "header.php";
?>
<div class="wrapper m-4">
  <div class="page-header clear-filter" filter-color="orange">
    <div class="page-header-image" data-parallax="true" style="background-image:url('./assets/img/header.jpg');">
    </div>
    <div class="container row d-flex">
      <div class=" brand col-md-5">
        <!-- <img class="n-logo mt-6" src="./assets/img/now-logo.png" alt=""> -->
        <!-- <img width="150" src="./assets/img/epustakalaya.png" alt=""> -->
        <h1 class="h1-seo"><?php echo $codename ;?></h1>
        <h3><?php //echo $description ;?></h3>
      </div>
      <div class="brand col-md-6 text-center">
        <!-- Designed and -->
        <!-- <a href="http://invisionapp.com/" target="_blank">
          <img src="./assets/img/invision-white-slim.png" class="invision-logo" /> -->
        <!-- </a>
          Coded by 
        <strong><span><img width="40px" class="invision-logo" src="assets/img/logo.png"> GDC Udhampur</span></strong> -->
        <h5><i class="fa fa-shield-alt"></i>&nbsp;
          Login to the portal</h5>
        <div  id="firebaseui-auth-container"></div>
      </div>
    </div>
    
  </div>
</div>
<?php
include 'footer.php';
?>

<script>
    var ui = new firebaseui.auth.AuthUI(firebase.auth());
    ui.start('#firebaseui-auth-container', uiConfig);

</script>