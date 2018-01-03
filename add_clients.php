<?php
/*
Remote Power Management
Tadas Ustinavičius

Vilnius,Lithuania.
2017-05-25
*/
include ('functions/config.php');
require_once('functions/functions.php');
if (!is_admin()){
    header ("Location: $serviceurl/?error=1");
    exit;
}
set_lang();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title><?php echo _("Remote Power Management - add users");?></title>
  <!-- RPM JavaScript -->
  <script src="inc/js/rpm.js"></script>
</head>
<body>
    <form method="post" action="add_clients_do.php">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title"><?php echo _("Add client machines"); ?></h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
            <textarea class="form-control" rows="10" id="Clients" name="Clients"></textarea>
            <small class="text-muted"><?php echo _("Enter a client list in comma separated format. Each client in a new line. e.g. client1,00:00:00:00:00:00<br>client2,11:11:11:11:11:11");?></small>
        </div>
    </div>
    <div class="modal-footer">
        <div class="clearfix"></div>
        <button type="button" id="ClientSubmit" class="btn btn-primary" data-dismis="modal"><?php echo _("Submit");?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _("Close");?></button>
    </div>
</div>
</form>
</body>
</html>