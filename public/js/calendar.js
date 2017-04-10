$( function() {
$( "#datepicker" ).datepicker({dateFormat: "yy-mm-dd"});
} );
$('#timepicker').blur(function() {
    var timeFormat = /^\s*([1-9]|0[1-9]|1[0-2]):[0-5][0-9]\s*$/i;
    if (!timeFormat.test(this.value)){
        $('#notifyTime').text('The time you entered is invalid. The accepted format is XX:XX or X:XX in civilian time / 12-hour clock.');
        $("#submit_form").prop("disabled",true);
        $('#error_box').show();
    } else {
        $('#notifyTime').text('');
        $("#submit_form").prop("disabled",false);
        $('#error_box').hide();
    }
});
