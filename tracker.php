<?php
chdir ("/var/www/" . explode (".",$_SERVER ["HTTP_HOST"])[0]);
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "anneli/ui.php" ;

$cols = json_decode (file_get_contents("anneli/assets/json/issue-tracker.json"), true);
$basename = $_SERVER['REQUEST_URI'] ;
$data = sql_exec ("SELECT * from store where uid = '$uid' and module = '$basename' order by auto_id desc", false);
// var_dump ($cols);
?>

<div class="section">
  <div class="container-md">
    <div class="row m-4">
      <div class="alert bg-primary shadow">
        <h4>
          <?php echo $codename ;?> Issue Tracker
          <i class="fas fa-flag"></i>
        </h4>
        <?php echo $description ;?>
      </div>
    </div>

    <?php 
      ui_table ($data, $cols, "Issue");
      ui_table_dialog ($cols, "Issue");
    ?>

  </div>
</div>

<?php
include "anneli/footer.php" ;
?>

<script>

</script>
