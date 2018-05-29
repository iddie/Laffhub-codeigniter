
</div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          
        </div>
        <strong>Copyright &copy; <?php echo date('Y');?> <a style="color:#DA7659;" href="http://www.laffhub.com" target="_blank">LaffHub</a>.</strong> All rights reserved.
      </footer>

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div>
    <script src="js/jquery-1.12.4_min.js"></script>  
    <script src="js/general.js"></script>
    <?php (isset($pagejs)) ? $this->load->view($pagejs) : null ?>
    <script src="js/jquery.dialog.js"> </script>
    <script src="js/app.min.js"></script>
    <script>
    $(function(){
      $('#ancMenuSignOut').click(function(e){
        e.preventDefault();
        invokeSignOut();
      });
      function invokeSignOut(){
        var m="Signing out will abort every active process and unsaved data will be lost. Do you still want to sign out? (Click <b>Continue</b> to proceed or <b>Cancel</b> to abort)";

 dialog.confirm({
  title: "Sign Out",
  message: m,
  cancel: "Cancel",
  button: "Continue",
  required: false,
  callback: function(value){
    if(value){
      window.location.href='<?php echo site_url("Logout"); ?>';
    }
  }
});
        }
    });
    </script>
  </body>
</html>
