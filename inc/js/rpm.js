$(document).ready(function(){
    var global_shutdown = 0;
    function refreshGlobalShutdown(){
        $.getJSON("inc/infrastructure/GlobalShutdownInfo.php", {},  function(json){
            $('#GlobalShutdownTimeInput').val(json.global_shutdown_time);
            global_shutdown = json.global_shutdown;
            if(json.global_shutdown == 1){
                $('#GlobalShutdownCheckBox').removeClass('fa-square-o');
                $('#GlobalShutdownButton').removeClass('btn-default');
                $('#GlobalShutdownTimeInput').attr("disabled",false);
                $('#GlobalShutdownCheckBox').addClass('fa-check-square-o');
                $('#GlobalShutdownButton').addClass('btn-success');
            }
            else {
                $('#GlobalShutdownCheckBox').removeClass('fa-check-square-o');
                $('#GlobalShutdownButton').removeClass('btn-success');
                $('#GlobalShutdownCheckBox').addClass('fa-square-o');
                $('#GlobalShutdownButton').addClass('btn-default');
                $('#GlobalShutdownTimeInput').attr("disabled",true);
            }

        });
    }
//==================================================================
    function redrawGlobalShutdown(){
        $('#GlobalShutdownTimeFormGroup').removeClass('has-warning');
        $('#GlobalShutdownTimeFormGroup').addClass('has-success');
        setTimeout(function(){
            $('#GlobalShutdownTimeFormGroup').removeClass('has-success');
        }, 2000);
    }
//==================================================================
    function updateGlobalShutdown(change_to, shutdown_time){
        if (!shutdown_time)
            shutdown_time = '23:59';
        $.post({
            url : 'inc/infrastructure/GlobalShutdown.php',
            data: {
                change_to: change_to,
                shutdown_time: shutdown_time,
            },
            success:function () {
                refreshGlobalShutdown();
                redrawGlobalShutdown();
            }
        });
    }
//==================================================================
    function updateDatatable() {
        $('#dataTable1').DataTable().ajax.reload(null, false);
    }
//==================================================================
    function redrawInfoPanels(){
        var db_info = new Array();
        $.get('inc/infrastructure/DBInfo.php', function(data){
            db_info = data.split('\n');
            $('#total_clients_info').text(db_info[0]);
            $('#total_groups_info').text(db_info[1]);
            $('#clients_on_info').text(db_info[2]);
            $('#clients_off_info').text(db_info[3]);
            $('#event-log').html(db_info[4]);
        });
    }
//==================================================================
    function changeServiceState(){
        $.post({
            url : 'inc/infrastructure/ChangeServiceState.php',
            data: {
                parameter: 'rpm_state',
                value: document.getElementById('ServiceState').value,
            },
            success:function(){
                redrawInfoPanels();
                refreshStateButtons();
            }
        });
    }
//==================================================================
function refreshStateButtons(){
    $.getJSON('inc/infrastructure/ReadServiceState.php', function(data){
        if (data.state == 0){
            $('#ServiceState').removeClass('btn-success').addClass('btn-default');
            $("#ServiceState").html('<i class="fa fa-square-o"> ' + data.status_text);
            document.getElementById('ServiceState').value = 0;
        }
        else{
            $('#ServiceState').removeClass('btn-default').addClass('btn-success');;
            $("#ServiceState").html('<i class="fa fa-check-square-o"> ' + data.status_text);
            document.getElementById('ServiceState').value = 1;
        }
    });
}
//==================================================================
function deleteClient(clientid){
    if (confirm('Are you sure?')) {
        $.post({
            url : 'inc/infrastructure/DeleteClients.php',
            data: {
                clientid: clientid
            },
            success:function(){
                redrawInfoPanels();
                updateDatatable();
            },
        });
    }
}
//==================================================================
    $('#GroupSelect').change( function() {
        oTable
            .columns(2)
            .search(this.value)
            .draw();
    });
//==================================================================
    $('#GlobalShutdownEnable').click(function() {
        var change_to = 0;
        if (global_shutdown == 1)
            change_to = 0;
        else
            change_to = 1;
        updateGlobalShutdown(change_to,  $('#GlobalShutdownTimeInput').val());
    });
//==================================================================
    $('#GlobalShutdownForm #GlobalShutdownTimeInput').bind('input', function(){
        $('#GlobalShutdownTimeFormGroup').addClass('has-warning');
    });
//==================================================================
    $("#GlobalShutdownTimeInput").keypress(function (e) {
        if (e.which == 13) {
            if($('#GlobalShutdownForm')[0].checkValidity()){
                updateGlobalShutdown(global_shutdown,  $('#GlobalShutdownTimeInput').val());
            }
        }
    });
//==================================================================
    $("#ClientSubmit").click(function(){
        $.post({
            url : 'inc/infrastructure/AddClients.php',
            data: {
                clients: $('#Clients').val(),
            },
            success:function () {
                updateDatatable();
                redrawInfoPanels();
                $(function () {
                    $('#smallScreen').modal('toggle');
                });
            },
        });
    });
//==================================================================
    $("#SelectAll").click(function(){
        $('.clientid').not(this).prop('checked', this.checked);
    });
//==================================================================
    $('#dataTable1').on("click", "a.DeleteClientButton", function(){//since table items are dynamically generated, we will not get ordinary .click() event
        deleteClient($(this).data("id"));
    });
//==================================================================
    $('#RefreshButton').click(function(){
        updateDatatable();
    });
//==================================================================
    $("#ServiceState").click(function(){
        changeServiceState();
    });
//==================================================================
    $("#PowerSubmit").click(function(){
        $.post({
            url: 'inc/infrastructure/PM.php',
            data: $('.clientid').serialize(),
            success: function(data) {
                $('#response').html(data);
                redrawInfoPanels();
                updateDatatable();
            },
        });
    });
//==================================================================
    redrawInfoPanels();
    refreshStateButtons();
    refreshGlobalShutdown();
});