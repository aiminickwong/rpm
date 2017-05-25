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
    function redrawGlobalShutdown(){
        $('#GlobalShutdownTimeFormGroup').removeClass('has-warning');
        $('#GlobalShutdownTimeFormGroup').addClass('has-success');
        setTimeout(function(){
            $('#GlobalShutdownTimeFormGroup').removeClass('has-success');
        }, 2000);
    }
    function updateGlobalShutdown(change_to, shutdown_time){
        if (!shutdown_time)
            shutdown_time = '23:59';
        $.post({
            url : "inc/infrastructure/GlobalShutdown.php",
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
    $('#GlobalShutdownEnable').click(function() {
        var change_to = 0;
        if (global_shutdown == 1)
            change_to = 0;
        else
            change_to = 1;
        updateGlobalShutdown(change_to,  $('#GlobalShutdownTimeInput').val());
    });
    $('#GlobalShutdownForm #GlobalShutdownTimeInput').bind('input', function(){
        $('#GlobalShutdownTimeFormGroup').addClass('has-warning');
    });
    $("#GlobalShutdownTimeInput").keypress(function (e) {
        if (e.which == 13) {
            if($('#GlobalShutdownForm')[0].checkValidity()){
                updateGlobalShutdown(global_shutdown,  $('#GlobalShutdownTimeInput').val());
            }
            //var validator = $("#GlobalShutdownForm");

            //validator.element( "#GlobalShutdownTimeInput" );
        }

    });


    refreshGlobalShutdown();
});