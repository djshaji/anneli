<?php
function post_json ($data) {
    $data_string = json_encode($data);                                                                                   
                                                                                                                         
    $ch = curl_init('://post.php');                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
    );                                                                                                                   
                                                                                                                         
    $result = curl_exec($ch);    
    return $result;
}

function info ($msg, $type = "primary") {
    echo "<div class='alert m-2 alert-$type'>" ;
    echo '<i class="fa mr-1 fa-info-circle" aria-hidden="true"></i>';
    print_r ($msg) ;
    echo "</div>" ;
}

function colors_dialog () {
    include_once ("colors.php") ;
    ?>
    <div class="modal fade" data-backdrop="false" id="colors" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class='text-dark'><i class="fas fa-cog"></i>&nbsp;
              Settings</h5>

              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

          </div>
          <div class="modal-body d-flex justify-content-center">
            <!-- <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div> -->
            <div class="col-md-3">
              <b class="lead text-dark">Select Skin</b><br>
              <select onchange="preview_theme ()" class="form-select" id="skin">
                <option value="">Default</option>
                <?php
                  $skins = scandir ("anneli/assets/css/themes") ;
                  foreach ($skins as $s) {
                    if ($s =='.' || $s =='..')
                      continue ;
                    echo "<option value='$s'>".ucwords ($s)."</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-md-3">
              <b class="lead text-dark">Select Theme</b><br>
              <select onchange="preview_theme ()" class="form-select" id="color-schemes">
                <option value="">Default</option>
              </select>
            </div>
            <div class="col-md-3">
              <b class="lead text-dark">Select Font</b><br>
              <select onchange="preview_theme ()" class="form-select" id="fonts">
                <option value="">Default</option>
              </select>
            </div>
            <div class="col-md-3">
              <b class="lead text-dark">Select Icon Theme</b><br>
              <select onchange="preview_theme ()" class="form-select" id="icons">
                <option value="">Default</option>
                <?php
                  $skins = scandir ("anneli/assets/css/icons") ;
                  foreach ($skins as $s) {
                    if ($s =='.' || $s =='..')
                      continue ;
                    echo "<option value='$s'>".ucwords ($s)."</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" onclick="preview_theme ()"><i class="fas fa-image"></i>&nbsp;Preview</button>
            <button type="button" class="btn btn-success" onclick="set_theme ()"><i class="fas fa-check-circle"></i>&nbsp;Apply</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>
      </div>
    </div>

    <?php
}

function colors_dialog_old () {
    include_once ("colors.php") ;
    ?>
    <div class="modal fade" data-backdrop="false" id="colors" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class='text-dark'><i class="fas fa-cog"></i>&nbsp;
              Settings</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body d-flex justify-content-center">
            <!-- <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div> -->
            <div class="col-md-6">
              <b class="lead text-dark">Select Theme</b><br>
              <select onchange="preview_theme ()" class="form-control" id="color-schemes">
                <option value="">Default</option>
              </select>
            </div>
            <div class="col-md-6">
              <b class="lead text-dark">Select Font</b><br>
              <select onchange="preview_theme ()" class="form-control" id="fonts">
                <option value="">Default</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" onclick="preview_theme ()"><i class="fas fa-image"></i>&nbsp;Preview</button>
            <button type="button" class="btn btn-success" onclick="set_theme ()"><i class="fas fa-check-circle"></i>&nbsp;Apply</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>
      </div>
    </div>

    <?php
}

function enable_error_reporting () {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

}

function array_key ($array, $key) {
  if (! isset ($array [$key]))
    $array [$key] = array () ;
}

function require_login ($redirect = false) {
  global $uid ;
  if ($uid == null && $redirect) {
    echo "
      <div class='alert alert-danger h3 m-3 p-2'>Login to continue</div>
    ";
    include "footer.php" ;
    echo '<script>
      location.href="/login.php" ;
    </script>';
    die () ;
  } else if ($uid == null) {
    ?>
    <script>
    // uiConfig = uiConfigFaculty ; // this is brilliant
    </script>
    <div class="wrapper m-4">
      <div class="page-header clear-filter" filter-color="orange">
        <div class="page-header-image" data-parallax="true" >
        </div>
        <div class="row">
          <div class=" brand d-none col-md-5">
            <!-- <img class="n-logo mt-6" src="./assets/img/now-logo.png" alt=""> -->
            <!-- <img width="150" src="./assets/img/epustakalaya.png" alt=""> -->
            <h1 class="h1-seo"><?php echo $codename ;?></h1>
            <h3><?php //echo $description ;?></h3>
          </div>
          <div class="brand border shadow p-3 col-md-12 text-center">
            <!-- Designed and -->
            <!-- <a href="http://invisionapp.com/" target="_blank">
              <img src="./assets/img/invision-white-slim.png" class="invision-logo" /> -->
            <!-- </a>
              Coded by 
            <strong><span><img width="40px" class="invision-logo" src="assets/img/logo.png"> GDC Udhampur</span></strong> -->
            <h5><i class="fa fa-shield-alt"></i>&nbsp;
              Login to the portal</h5>
            <div  id="firebaseui-auth-container">
            </div>
            <button onclick="location.reload ()" class="btn m-2">
              <i class="fas fa-sync"></i>
              Refresh page
              <span id="login-spinner2" class="ms-2 spinner-border text-primary spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>           

          </div>
        </div>
        
      </div>
    </div>
  <?php
  include __DIR__ .'/footer.php';
    die () ;
  }    
}

function feline () {
  echo __FILE__, ': ', __LINE__ . '<br>' ;

}

function check_root () {
  global $root, $uid ;
  if (! in_array ($uid, $root)) {
      printf ('<script>
      Swal.fire ("Unauthorized", "You are not authorized to perform this operation.", "error").then((e)=>{ 
      location.href = "%s"
      })

      </script>', $_SERVER['HTTP_REFERER']);
      include "footer.php";
      die () ;
  }

}

function is_root () {
  global $root, $uid ;
  if (! in_array ($uid, $root)) {
      return false ;
  }

  return true ;

}

function print_table ($data, $cols, $doc_cols, $doc_names, $url, $highlight = -1) {
    ?>
    <div class="table-responsive">
      <!-- <h4 class="p-3">
      PROVISIONAL 1ST SELECTION LIST OF CANDIDATES WHO HAVE APPLIED ONLINE FOR ADMISSION TO BA/B.Sc./B.Com/BBA/BCA SEMESTER-I FOR THE SESSION-2021-22
      </h4> -->
      <table class="table table-hover table-bordered" id="table" style="page-break-inside:auto;">
        <thead>
          <th>S. No</th>
    <?php
    foreach ($cols as $c) {
      if (gettype ($c) == "string")
        printf ("<th>%s</th>", str_replace ("_", " ", ucwords ($c))) ;
      else {
        echo "<th>" ;
        foreach ($c as $d) {
          printf ("%s<br>", str_replace ("_", " ", ucwords ($d))) ;
        }
        echo "</th>";
      }
    }

    if ($doc_cols != null) echo "<th>Documents</th>" ;
    echo "</thead>" ;
    echo '<tbody>' ;
    $counter = 1 ;
    foreach ($data as $d) {
      if ($counter > $highlight && $highlight != -1 && false)
        $flag = "class='alert alert-info'";
      echo "<tr $flag style='page-break-inside:avoid; page-break-after:auto;'><td>$counter</td>" ;
      $counter ++ ;
      foreach ($cols as $c) {
        if (gettype ($c) == 'string') {
          if ($url != null) 
            printf ("<td><a target='_blank' href='%s?id=%s'>%s</a></td>", $url, $d ['auto_id'], $d [$c]) ;
          else
            printf ("<td>%s</td>", $d [$c]) ;
        }
        else {
          echo "<td>";
          foreach ($c as $dc) {
            $v = $d [$dc] ;
            if ($d [$dc] == '')
              $v = '<span class="text-danger h3 bold"><i class="fas fa-times"></i></span>' ;
            printf ("%s<br>", $v) ;
          }
          echo "</td>" ;
        }
      }

      if ($doc_cols != null) {
        echo "<td>" ;
        for ($i = 0 ; $i < sizeof ($doc_cols) ; $i ++) {
          if ($d [$doc_cols [$i]] != null)
            printf ("<a href='%s' class='btn-link'>%s</a><br>", $d [$doc_cols [$i]], $doc_names [$i]);
        }
        echo "</td>" ;
      }
      echo "</tr>" ;
    }
    ?>
        </tbody>
      </table>

      <div class="border border-info p-3 m-2">
            Note: <br>
                1. Selection of a candidate is purely provisional and admission in Semester-I is subject to verification of original documents and correct information provided by him/her in pre admission form. <br>
                2. Selection list is drawn on the basis of merit i.e. marks obtained in class 12th.  Mere mention in the Selection List does not entitle an applicant for admission. <br>
                3. Five percent marks have been added to the total marks secured by the students who belong to the reserved categories.<br>
                4. Female candidates who have applied for Sericulture, Biotechnology, Electronics, Statistics, Geology, BBA and BCA subjects are considered on the basis of the marks obtained in class 12th .   However, female candidates with 80% and above marks are also considered in Science stream except for the aforementioned subjects.    <br>
                <!-- 5. Students with a gap of one or more years shall be considered in the second list.
                6. Students whose pre-admission forms have been rejected are directed to apply again through offline mode in the college subject to the availability of seats. -->
                
      </div>
    </div>
    
    <div style='page-break-after:auto'></div>
    <?php
}

function messages_get_unread () {
  global $uid, $auth ;
  $un = 0 ;
  $sql = "SELECT * from chat where uid = '$uid' or sender = '$uid'";
  $data = sql_exec ($sql, false);
  
  $contacts = [];
  foreach ($data as $d) {
    foreach (["uid", "sender"] as $u)
      if ($d [$u] != $uid && ! in_array ($d[$u], $contacts))
        array_push ($contacts, $d [$u]);
  
  }  
  
  foreach ($contacts as $c) {
    $sender = $auth -> getUser ($c) ;
    $last_read = sql_exec ("SELECT stamp from chat where uid='$uid' and sender = '".$sender ->{"uid"}."' ORDER BY stamp DESC LIMIT 1", false)[0]["stamp"];
    $unread = sizeof (sql_exec ("SELECT stamp from chat where uid='".$sender ->{"uid"}."' and stamp > '$last_read' ORDER BY stamp DESC", false));
    $un = $un + $unread ;
  }

  return $un ;
}

function get_file ($path) {
  header("X-Sendfile: $path");
  header("Content-type: application/octet-stream");
  header('Content-Disposition: attachment; filename="' . basename($path) . '"');
}

function is_cli () {
  return php_sapi_name()==="cli" ;
}

function check_cli () {
  if (! is_cli ()) {
    echo system ("fortune"); print ("<br><br>");
    die ("This script cannot be run from the server.") ;
  }
}

function require_root () {
  if ($uid != $root_user) {
    die ("403 Unauthorized") ;
  }
}

function parse_csv ($string, $index = 0) {
  $text = explode ("\n", $string) ;
  $header = null ;

  $data = array () ;
  foreach ($text as $line) {
    if ($header == null)
      $header = explode (",", $line) ;
    
    $vector = explode (",", $line);
    for ($i = 0 ; $i < sizeof ($vector) ; $i ++) {
      if (! isset ($data [$vector [$index]]))
        $data [$vector [$index]] = array () ;
      
      $data [$vector [$index]][$header [$i]] = $vector [$i] ;
    }
  }

  return $data ;
}
?>