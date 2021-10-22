<?php if ($config ["footer"] && !isset ($_GET ['quiet']) && ! isset ($_GET[ 'print'])) { ?>
<div class="d-print-none" style="opacity: 80%; height: 40px;transition: all 0.4s; position: fixed;bottom:15px;left:15px;z-index: 9999;">
  <a href="https://wa.me/917006351732" type="button" class="btn btn-sm btn-success bmd-btn-fab">
    <i class="fab fa-whatsapp" style="font-size: 20;" aria-hidden="true"></i>
  </a>
  <a href="tel://+917006351732" type="button" class="btn btn-sm btn-danger bmd-btn-fab">
      <i class="fa fa-phone"  style="font-size: 20;" id='fab-phone'></i>
  </a>      
  <!-- <label class="badge badge-info p-2">Contact me</label> -->
</div>

<div id="footer" class="<?php echo $config ['footer-bg'];?> p-4 text-center">
  <div class="copyright">
    Made with &nbsp;<img width="40px" src="<?php echo $config ["logo"] ;?>"> and Coffee.
  </div>

</div>

<!-- mdl layout -->
<?php if ($config ['header'] != false) echo '</div>' ;?>

<!-- Modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5><i class="fa fa-shield-alt"></i>&nbsp;
          Login to the portal</h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>
      <div class="modal-body d-flex justify-content-center">
        <div  id="firebaseui-auth-container">
          <!-- <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div> -->

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
</body></html>
<?php } ?>
<?php 
  colors_dialog () ; 
  include "spinner.php";
  
?>
<script>
init ()
</script>
