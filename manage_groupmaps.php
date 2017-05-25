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
    exit;
}
set_lang();
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title><?php echo _("Remote Power Management - manage groups");?></title>  
</head>
<body>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title"><?php echo _("Add clients to group"); ?></h4>
        </div>
        <div class="modal-body">
	 <div class="form-group">


    		    <div class="row">
		        <div class="col-xs-5">
            <select name="multiselect" id="multiselect" class="form-control" size="20" multiple="multiple">
            </select>
        </div>
        
        <div class="col-xs-2">
            <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
            <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
            <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
            <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
        </div>
        
        <div class="col-xs-5">
            <select id="multiselect_to" name="multiselect_to" class="form-control" size="20" multiple="multiple"></select>
        </div>
    </div>



	    <div class="row">
		<div class="col-md-4">
		    <label for="grouplist" class="text-muted"><?php echo _("Group");?></label>
		    <select class="input-small form-control" id="grouplist" name="grouplist">
            <?php $group_array=get_SQL_array("SELECT * FROM groups ORDER BY name");
            $x=0;
            while ($x < sizeof($group_array)){
                echo '<option value="' . $group_array[$x]['id'] . '">' . $group_array[$x]['name'] . '</option>';
                ++$x;
            }?>
            </select>
        </div>
	    </div>
	    <div class="row">
		<div class="col-md-12">
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
</body>


<script>
function load_list(groupid){
    $.getJSON("clients_in_group.php?side=from&groupid="+groupid, {},  function(json){
        $('#multiselect').empty();
            $.each(json, function(i, obj){
                $('#multiselect').append($('<option>').text(obj.text).attr('value', obj.val));
            });
    });
    $.getJSON("clients_in_group.php?side=to&groupid="+groupid, {},  function(json){
        $('#multiselect_to').empty();
            $.each(json, function(i, obj){
                $('#multiselect_to').append($('<option>').text(obj.text).attr('value', obj.val));
            });
    });
}
$('#grouplist').on('change', function(){
    $groupid=$('#grouplist').val();
    load_list($groupid);
});
$(document).ready(function(){
    $groupid=$('#grouplist').val();
    load_list($groupid);
    $("#submit").click(function(){
        var multivalues="";
        $("#multiselect_to option").each(function(){
            multivalues += $(this).val() + ",";
        });
        $.post("inc/infrastructure/ManageGroupmaps.php",{
            groupid: $('#grouplist').val(),
            clientlist: multivalues
        });
        update_datatable1();
        $(function () {
            $('#mediumScreen').modal('toggle');
        });
    });
    $('#multiselect').multiselect();
});
</script>
</html>