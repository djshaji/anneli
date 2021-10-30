<?php
include "header.php" ;

$info = array () ;
$info ['build'] = "αlpha ". exec ("git rev-list HEAD --count");
$info ['build date'] = exec ("git log -1 --format=%cd") ;
$info ['system'] = exec ("uname -sr") ;
$info ['server'] = exec ("rpm -q httpd") ;
$info ['php'] = exec ("rpm -q php") ;
$info ['python'] = exec ("python -V") ;
$info ['memory'] = exec ("free -th") ;
$info ['theme'] = $theme ;
$info ['font'] = $font ;
 
?>

<div class="jumbotron">
  <h1 class="display-4"><?php echo $codename ;?></h1>
  <p class="lead">
    <?php echo $description ;?>
    Designed and Coded by <a href="https://shaji.in">Shaji</a>. <a href="#contact">Contact me</a> or <a href="https://shaji.in">visit my website</a>.
  </p>
  <hr class="my-4">
  <p  class="lead">
    The <?php echo $description ;?> is designed to conserve, popularize and promote 
    <b>Native Scripts of Dogri </b>. <br>
    <ul class="list-group">
      <li class="lead active list-group-item list-group-item-action">The project has the following objectives</li>
      <li class="list-group-item list-group-item-action">Protect the Native Dogri Script of <b>Namme Dogra Akkhar</b> </li>
      <li class="list-group-item list-group-item-action">Protect the Native Dogri Script of <b>Takri</b> </li>
      <li class="list-group-item list-group-item-action">Teaching the script to students</li>
      <li class="list-group-item list-group-item-action">Promoting NLP research and Machine Translation in Dogri native scripts</li>
    </ul>
    <br>
    <h3>How you can help</h3>
    <p>
    <ol class="list-group">
      <li class="lead active list-group-item list-group-item-action">To contribute to the conservation of Native Dogri Scripts</li>
      <li class="list-group-item list-group-item-action">Correct automatically transliterated words available on the portal using the <b class="text-primary">Read More</b> feature</li>
      <li class="list-group-item list-group-item-action">Post new articles on the portal using the <b class="text-primary">Post New Article</b> feature by clicking this icon <i class="text-primary fas fa-plus-circle" style="font-size: 20;" aria-hidden="true"></i></li>
    </ol>
    </p>
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

    For any comments, suggestions or feedback, or to collaborate on a research project / paper in Dogri Scripts and NLP, call or WhatsApp me.
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
include "footer.php" ;
?>