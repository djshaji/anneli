<?php
function ui_table ($data, $cols, $title, $database) {
  $id = str_replace (" ", "-", $title);
  ?>
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
          $data_json = array () ;

          foreach ($data as $d) {
            // var_dump ($d ["auto_id"]);
            $data = json_decode ($d ["data"], true);
            $data_json [$d ['auto_id']] = $data ;
            // var_dump ($data);
            echo "<tr><td>$counter</td>";
            $counter ++ ;
            foreach ($cols as $name => $array) {
              if ($array ["type"] == "select")
                printf ("<td>%s</td>", $array ["options"][$data [$name]]);
              else if ($array ["type"] != "file")
                printf ("<td>%s</td>", ucwords ($data [str_replace (" ", "_", $name)]));
              else {
                foreach ($data [str_replace (" ", "_", $name)] as $filename => $path) {
                  printf ("<td><a style=\"max-width: 150px;\" class='text-truncate btn btn-secondary' href='/anneli/api/file?file=%s'>%s</a></td>", basename ($path), $filename);
                }
              }
            }

            printf (
              "<td>
                <button data-bs-toggle=\"modal\" data-bs-target=\"#%s\" onclick='load_form (\"%s\", this);' id='%s' class='btn btn-success m-1'><i class=\"fas fa-edit\"></i></button>
                <button id='%s' onclick='delete_entry (this, \"%s\") ;' class='btn btn-danger m-1'><i class=\"fas fa-minus-circle\"></i></button>
              </td>", $id, $id, $d ["auto_id"], $d ["auto_id"], $database
            ) ;

            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  <?php
  printf (
    "<script>
      let json_data = JSON.parse ('%s') ;
    </script>",
    json_encode ($data_json)
  ) ;
}

function ui_table_dialog ($cols, $title, $database) {
  $id = str_replace (" ", "-", $title);
  ?>
    <div class="row">
      <button onclick="form_reset ('<?php echo $id;?>')" class="shadow btn btn-lg btn-primary mx-auto col-md-2 m-2" data-bs-toggle="modal" data-bs-target="#<?php echo $id;?>">
        <i class="fas fa-plus-circle"></i>
        Add <?php echo $title ;?>
      </button>
    </div>  
  <?php
  ?>
  <!-- Modal -->
  <div class="modal fade" id="<?php echo $id;?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Add <?php echo $title ;?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post"  enctype="multipart/form-data" id="<?php echo $id;?>-form" class="row p-3 input-group">
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
                    echo "<select id='$element' name='$element' class='form-select $class' required>" ;
                    echo "<option value=''>$element</option>";
                    foreach ($parameters ["options"] as $o => $_o) {
                      echo "<option value='$o'>$_o</option>";
                    }

                    echo "</select>";
                    break ;
                  case "textarea":
                    $style .= "height: 100px";
                  case "date":
                  case "file":
                    // $fname .= "[]";
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
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times-circle"></i>&nbsp;&nbsp;Close</button>
          <button onclick="submit_form ('<?php echo $title ;?>', '<?php echo $database ;?>')" id="<?php echo $id;?>-submit" type="button" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;&nbsp;Save</button>
          <button onclick="update_form ('<?php echo $title ;?>', '<?php echo $database ;?>')" id="<?php echo $id;?>-update" type="button" class="d-none btn btn-warning"><i class="fas fa-sync"></i>&nbsp;&nbsp;Update</button>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>