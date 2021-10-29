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
// $to = "OmAUIf45WFbgzeDAY340j8Qa2R22";
$to = $_GET ['to'];
echo "<script>const to = '$to'</script>";

$sql = "SELECT * from chat where (uid = '$uid' and sender = '$to') or (sender = '$uid' and uid = '$to') order by stamp DESC limit 30" ;
$data = sql_exec ($sql, false);
$data = array_reverse ($data);
$sender = $auth -> getUser ($to) ;
?>

<!-- <script src="https://www.gstatic.com/firebasejs/7.14.4/firebase-messaging.js"></script> -->
<script src="/anneli/chat.js?<?php echo time () ;?>"></script>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 alert bg-info h3 shadow mt-3">
        <a href="/anneli/messages" class="btn btn-info me-2"><i class="fas h1 fa-arrow-circle-left"></i></a>
        <?php
          if ($sender -> {"photoUrl"} != null)
            printf ("<img src='%s' width='32'>", $sender -> {"photoUrl"} ) ;
          else
            echo "<i class=\"fas fa-user-circle\"></i>";
        ?>
        <?php echo $sender -> {"email"} ;?>
      </div>
      <div class="col-md-8 list-group mt-0 p-3 pt-0" id="mcontainer">
        <?php
          foreach ($data as $d) {
            // echo $uid, ' ', $d ['uid'];
            if ($d ['uid'] != $uid) {
              $class = "active btn-lg card list-group-item list-group-item-action";
              $badge = "badge text-muted bg-secondary m-1";
            } else {
              $class = "text-end btn-lg list-group-item list-group-item-action";
              $badge = "badge text-white bg-primary m-1";
            }

            $message = $d ['message'];
            $time = date ("F j, y g:i a", $d ['stamp']);
            echo "<a href='#' class='$class'>$message&nbsp;<sup class='$badge' style='opacity:80%;font-size:60%'>$time</sup></a>";
          }
        ?>
        <!-- <a href="#" class="active btn-lg card list-group-item list-group-item-action">Hi&nbsp;<sup class="badge text-muted bg-secondary m-1" style="opacity:80%;font-size:60%">11:30 pm</sup></a>
        <a href="#" class="text-end btn-lg list-group-item list-group-item-action">Hello&nbsp;<sup class="badge text-white bg-primary m-1" style="opacity:80%;font-size:60%">11:32 pm</sup></a>
        <a href="#" class="list-group-item btn-lg card list-group-item-action active">How are you&nbsp;<sup class="badge text-muted bg-secondary m-1" style="opacity:80%;font-size:60%">11:40 pm</sup></a> -->
      </div>

      <div class="col-md-6 card shadow m-3 border border-primary">
        <div class="form-floating">
          <textarea class="form-control" placeholder="Type a message" id="message" style="height: 100px"></textarea>
          <label for="message">Type a message</label>
        </div>        
      </div>

      <div class="col-2 mt-3">
        <button onclick="chat_send_message ();" class="m-1 btn btn-lg btn-primary">
          <i class="fas fa-paper-plane"></i>          
          Send
        </button>
        <button class="btn m-1 btn-lg btn-info">
          <i class="fas fa-image"></i>        
        </button>
        <button class="btn m-1 btn-lg btn-success">
          <i class="fas fa-video"></i>        
        </button>
      </div>

    </div>
  </div>
</section>
<script>
  ui ("message").focus ()
  ui ("message").addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.code == 'Enter')
      chat_send_message ()
  });

  chat_register_token ()
  chat_init ()
</script>
<?php
include "anneli/footer.php" ;
?>