$(document).ready(function(){

    $(".dropdown-menu li a").click(function(){
      var selText = $(this).text();
      $(this).parents('.input-group-btn').find('.dropdown-toggle').html(selText+' <span class="caret"></span>');
    });

    $(".type li").click(function(){
      //Get the value
      var value = $(this).attr("value");
      //Put the retrieved value into the hidden input
      $("input[name='type']").val(value);
    });

    $('.fieldChecker').blur(function() {
        var correctFormat = /^[a-zA-Z0-9.,?!]*$/i;
        // var timeFormat = /^\s*([1-9]|0[1-9]|1[0-2]):[0-5][0-9]\s*$/i;
        if (!correctFormat.test(this.value) || this.value.trim() == "" ){
          // alert("using incorrect format");
            $('#notifyError').text('You are utilizing prohibited symbols');
            $("#submit_form").prop("disabled",true);
            $('#error_box').show();
        } else {

            $('#notifyError').text('');
            $("#submit_form").prop("disabled",false);
            $('#error_box').hide();
        }
    });

});
