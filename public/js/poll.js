function removeResponse(location) {
    window.location.href = location;
}

$(document).ready(function(){
    $('.btn-results').click(function(){

        var id = $(this).attr('id');
        var splt = id.split("_");
        var pollid = splt[1];
        $("#voting_options_" + pollid).hide();
        $("#results_" + pollid).show();

        $("#resultsBtn_" + pollid).hide();
        $("#optionsBtn_" + pollid).show();
    });

    $('.btn-options').click(function(){

        var id = $(this).attr('id');
        var splt = id.split("_");
        var pollid = splt[1];
        $("#voting_options_" + pollid).show();
        $("#results_" + pollid).hide();

        $("#resultsBtn_" + pollid).show();
        $("#optionsBtn_" + pollid).hide();
    });

});
