$(document).ready(function(){
    var global_shutdown = 0;
    function refreshGlobalShutdown(){
        $.getJSON("inc/infrastructure/GlobalShutdownInfo.php", {},  function(json){
            $('#GlobalShutdownTimeInput').val(json.global_shutdown_time);
            global_shutdown = json.global_shutdown;
            if(json.global_shutdown){
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
    function updateGlobalShutdown(){
        $.post({
            url : "inc/infrastructure/GlobalShutdown.php",
                data: {
                    change_to: 1,
                    shutdown_time: 2,
                    },
                success:function (data) {
                }
        });
    }
    $('#GlobalShutdownEnable').click(function() {
        var change_to = 0;
        if (global_shutdown)
            change_to = 0;
        else
            change_to = 1;
        updateGlobalShutdown();
//        updateGlobalShutdown(change_to,  $('#GlobalShutdownTimeInput').val());

    });
    refreshGlobalShutdown();
});