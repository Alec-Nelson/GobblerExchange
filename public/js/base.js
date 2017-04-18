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

});
