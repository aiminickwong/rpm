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
if (!check_session()){
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
    <form method="post" action="change_settings_do.php">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title"><?php echo _("Choose your language"); ?></h4>
        </div>
        <div class="modal-body">
	 <div class="form-group">
	      <div class="row">
            	    <div class="col-xs-5">
			<select class="form-control" id="locale">
			    <option>EN</option>
			    <?php $dir    = 'locale/';
			    $directories = scandir($dir);
			    $x=0;
			    while (!empty($directories[$x])){
				if ($directories[$x]!='.'&&$directories[$x]!='..'){
				    $localename = substr($directories[$x], strpos($directories[$x], "_") + 1);
				    echo "<option value=" . '"' . $directories[$x] .'"' .">$localename</options>\n";
				}
				++$x;
			    }
			    ?>
			</select>
		    </div>
		</div>
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
        $.ajax({
	type: "POST",
	url: "change_settings_do.php",
	async: false,
	data: {locale:$('#locale').val() }
	});
	document.location.reload();
	$('#smallScreen').modal('toggle');
    });
});

</script>
</html>