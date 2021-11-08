<?php
chdir ("/var/www/" . explode (".",$_SERVER ["HTTP_HOST"])[0]);
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;

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

    <div class="row table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <th>S. No</th>
          <?php foreach ($cols as $name => $array) {
            echo "<th>$name</th>" ;
          }
          ?>
          <th></th>
        </thead>
        <tbody>
          <?php 
          $counter = 1; 
          foreach ($data as $d) {
            $data = json_decode ($d ["data"], true);
            // var_dump ($data);
            echo "<tr><td>$counter</td>";
            $counter ++ ;
            foreach ($cols as $name => $array) {
              if ($array ["type"] != "file")
                printf ("<td>%s</td>", $data [str_replace (" ", "_", $name)]);
              else {
                foreach ($data [str_replace (" ", "_", $name)] as $filename => $path) {
                  printf ("<td><a style=\"max-width: 150px;\" class='text-truncate btn btn-secondary' href='/anneli/api/file?file=%s'>%s</a></td>", basename ($path), $filename);
                }
              }
            }

            printf (
              "<td>
                <button class='btn btn-success m-1'><i class=\"fas fa-edit\"></i></button>
                <button class='btn btn-danger m-1'><i class=\"fas fa-minus-circle\"></i></button>
              </td>",
            ) ;

            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="row">
      <button class="shadow btn btn-lg btn-primary mx-auto col-md-2 m-2" data-bs-toggle="modal" data-bs-target="#add-issue">
        <i class="fas fa-plus-circle"></i>
        Add Issue
      </button>
    </div>
  </div>
</div>

<?php
include "anneli/footer.php" ;
?>

<!-- Modal -->
<div class="modal fade" id="add-issue" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add Issue</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post"  enctype="multipart/form-data" id="form" class="row p-3 input-group">
          <?php
            foreach ($cols as $element => $parameters) {
              $class = $parameters ["class"] ;
              $style = "";
              $fname = $element ;
              if ($class = null) $class = "col-md-6";

              switch ($parameters ["type"]) {
                default:
                  break ;
                case "select":
                  echo "<select name='$element' class='form-select $class' required>" ;
                  echo "<option value=''>$element</option>";
                  foreach ($parameters ["options"] as $_o => $o) {
                    echo "<option value='$o'>$_o</option>";
                  }

                  echo "</select>";
                  break ;
                case "textarea":
                  $style .= "height: 100px";
                case "date":
                case "file":
                  $fname .= "[]";
                case "text":
                  printf (
                    '<div class="form-floating mb-3 %s">
                      <input multiple name="%s" onchange="this.classList.remove (\'is-invalid\');" required style="%s" type="%s" class="form-control" id="%s" placeholder="%s">
                      <label for="floatingInput">%s</label>
                    </div>', $class, $fname, $style, $parameters ["type"], $element, $element, $element
                  ) ;
                  break ;
              }
            }
          ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button onclick="submit_form ()" id="form-submit" type="button" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;&nbsp;Save</button>
      </div>
    </div>
  </div>
</div>
<script>
function submit_form () {
  form = ui ("form")
  for (i of form.querySelectorAll ("input")) {
    if (i.value == "") {
      Swal.fire(
        'Incomplete data',
        'Complete all details before saving.',
        'warning'
      ).then (function () {
        i.focus () ;
        i.classList.add ("is-invalid")
      })
      return ;
    }
  }

  formdata = form_to_json ("form") ;
  formdata.append ("module", location.pathname)
  db ("store", "insert", formdata, null, null, "json")
}
</script>
