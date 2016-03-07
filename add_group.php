<?php
/*
Remote Power Management
Tadas Ustinavičius
tadas at ring.lt

Vilnius University.
Center of Information Technology Development.


Vilnius,Lithuania.
2016-03-01
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
</head>
<body>
    <form method="post" action="add_clients_do.php">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title"><?php echo _("Add group"); ?></h4>
        </div>
        <div class="modal-body">
	 <div class="form-group">
	    <input type="text" class="form-control" id="groupname" placeholder="<?php echo _("Group name");?>">
	</div>

        </div>
        <div class="modal-footer">

	    <div class="clearfix"></div>
	    <button type="button" id="submit" class="btn btn-primary" data-dismis="modal"><?php echo _("Submit");?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _("Close");?></button>
        </div>
    </div>
    </form>
</body>
<script>
$(document).ready(function(){
    $("#submit").click(function(){
        $.post("add_group_do.php",
        {
          groupname: $('#groupname').val() 
        });
	update_datatable1();
	redraw_info_panels();
        $(function () {
	    $('#smallScreen').modal('toggle');
	});
    });
});

</script>
</html>