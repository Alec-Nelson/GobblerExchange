$(document).ready(function(){
  $('.dropdownoptions').click(function(){
    //sets hidden input to value of intended list option
    //date or popularity
    var value = $(this).attr("value");
    $("input[name='sortType']").val(value);
    //submits form for forum sorting
    //for onclick of new sort
    $('#forumsort').submit();
  });

});
