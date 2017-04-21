$(document).ready(function(){
  $('#isClassCB').click(function(){
    if($('#isClassCB').is(':checked'))
    {
      $( "#groupform" ).prop( "disabled", true );
      $( "#CRNform" ).prop( "disabled", false );

     }
     else
     {
       $( "#CRNform" ).prop( "disabled", true );
       $( "#groupform" ).prop( "disabled", false );
      }
      });

});
