<?php
chdir ("/var/www/" . explode (".",$_SERVER ["HTTP_HOST"])[0]);
include "config.php";
include "anneli/header.php" ;

$info = array () ;
$info ['build'] = "αlpha ". exec ("git rev-list HEAD --count") . " anneli ". exec ("cd anneli; git rev-list HEAD --count") ;
$info ['build date'] = exec ("git log -1 --format=%cd") ;
$info ['system'] = exec ("uname -sr") ;
$info ['server'] = exec ("rpm -q httpd") ;
$info ['php'] = exec ("rpm -q php") ;
$info ['python'] = exec ("python -V") ;
$info ['memory'] = exec ("free -th") ;
$info ['theme'] = $theme ;
$info ['font'] = $font ;
 
?>

<div class="jumbotron section m-4">
  <h1 class="display-4"><?php echo $codename ;?></h1>
  <p class="lead">
    <?php echo $description ;?>
    Designed and Coded by <a class="btn btn-primary" href="https://shaji.in">Shaji</a>. <a class="btn btn-primary" href="#contact">Contact me</a> or <a class="btn btn-primary" href="https://shaji.in">visit my website</a>.
  </p>
  <hr class="my-4">
  <p  class="lead">
    <?php echo $description ;?> 
    <br>

    <p>
    <div class="d-print-none" id="contact">
      <h3>Contact Me
        <a href="https://wa.me/917006351732" type="button" class="btn btn-sm btn-success bmd-btn-fab">
          <i class="fab fa-whatsapp" style="font-size: 20;" aria-hidden="true"></i>
        </a>
        <a href="tel://+917006351732" type="button" class="btn btn-sm btn-danger bmd-btn-fab">
            <i class="fa fa-phone"  style="font-size: 20;" id='fab-phone'></i>
        </a>      
      </h3>
        <!-- <label class="badge badge-info p-2">Contact me</label> -->
    </div>

    </p>
  </p>

</div>

<section class="container m-3">
  <h3 class="p-1">System Information</h3>
  <div class="d-flex flex-row mb-3 table-responsive">
    <table class="table table-hover table-bordered border border-dark">
      <thead>
        <th>Component</th>
        <th>Version</th>
      </thead>
      <tbody>
      <?php foreach ($info as $i => $v) {
        printf (
          "<tr>
            <td>%s</td>
            <td>%s</td>
          </tr>", ucwords ($i), $v
        ) ;
      }
      ?>
      </tbody>
    </table>

  </div>
</section>
<?php
include "anneli/footer.php" ;
?>