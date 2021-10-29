<?php
chdir ("/var/www/" . explode (".",$_SERVER ["HTTP_HOST"])[0]);
include "config.php";
$theme = "red_blue";
$font = "Montserrat";
$skin = "materia";
// $config ['logo'] = 'anneli/assets/img/logow.png';
// $config ['header'] = false;
include "anneli/header.php" ;
include "anneli/db.php" ;
$sql = "SELECT * from chat where uid = '$uid' or sender = '$uid'";
$data = sql_exec ($sql, false);

$contacts = [];
foreach ($data as $d) {
  foreach (["uid", "sender"] as $u)
    if ($d [$u] != $uid && ! in_array ($d[$u], $contacts))
      array_push ($contacts, $d [$u]);

}
?>

<div class="section">
  <div class="row justify-content-center">
    <ul class="list-group card col-md-6 m-3 p-3 shadow">
      <?php foreach ($contacts as $c) {
              $sender = $auth -> getUser ($c) ;?>
        <a class="list-group-item h5 d-flex justify-content-between align-items-center" href="/anneli/chat?to=<?php echo $sender -> {"uid"};?>">
          <span>
            <?php
              if ($sender -> {"photoUrl"} != null)
                printf ("<img src='%s' width='32'>", $sender -> {"photoUrl"} ) ;
              else
                echo "<i class=\"fas fa-user-circle\"></i>";
            ?>
            &nbsp;
            <?php
              echo $sender -> {"email"} ;
              ?>
          </span>
          <!-- <span class="badge bg-primary rounded-pill">14</span> -->
        </a>
      <?php } ?>
      
    </ul>
  </div>
</div>

<?php
include "anneli/footer.php" ;
?>