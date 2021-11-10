<?php
chdir ("/var/www/" . explode (".",$_SERVER ["HTTP_HOST"])[0]);
include "config.php";
include "anneli/header.php" ;

$files = scandir ($config ["filesdir"] . "/". $uid) ;
?>

<div class="section">
  <div class="container-md">
    <div class="alert bg-primary m-4 text-white mdl-shadow--2dp">
      <h4><i class="fas fa-photo-video"></i>&nbsp;&nbsp;Media</h4>    
      <div class="ms-4 text-white"><?php echo exec ("du -shc " . $config ["filesdir"] . "/". $uid ) ; ?> of 1 GB used</div>
    </div>

    <div class="row m-4 row-cols-1 row-cols-md-3 g-4">
      <?php
        foreach ($files as $f) {
          if ($f [0] == '.') continue ;
          $path = $config ["filesdir"] . "/" . $uid . "/" . $f;
          ?>
            <div class="card mdl-shadow--4dp">
              <div class="card-header d-flex bg-info text-white">
                <span class="flex-fill align-self-center"><?php $m = mime_content_type ($f) ; if ($m) echo ucwords ($m) ; else echo "File";?></span>
                <button class="btn-danger btn btn-sm"><i class="fas fa-window-close"></i></button>
              </div>
              <div class="card-body">
                <h5 class="card-title"><?php echo explode (".", $f)[0] ;?></h5>
                <p class="card-text"><?php print (filesize ($path) / 1000.0) ;?> kB</p>
                <a href='/anneli/api/file?file=<?php echo $f ;?>' class="btn btn-success"><i class="fas fa-download"></i>&nbsp;&nbsp;Download</a>
              </div>
            </div>
          <?php
        }
      ?>
    </div>
  </div>
</div>

<?php
include "anneli/footer.php";
?>