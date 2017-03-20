$(document).ready(function(){
    $('.form-group').click(function(){
       var e = document.getElementById("month");
    var m = e.options[e.selectedIndex].value;
    console.log(m);
    var daysJan = 31;
    var daysFeb = 28; 
    var daysMar = 31;
    var daysApr = 30;
    var daysMay = 31;
    var daysJun = 30;
    var daysJul = 31;
    var daysAug = 31;
    var daysSep = 30;
    var daysOct = 31;
    var daysNov = 30;
    var dayDec = 31;
    
    if(m === "1")
    {
        $("#days").empty();
        for(i = 1; i <= daysJan; i++)
        {
            $("#days").append("<p>" + "January " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "2")
    {
        $("#days").empty();
        for(i = 1; i <= daysFeb; i++)
        {
            $("#days").append("<p>" + "February " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "3")
    {
        $("#days").empty();
        for(i = 1; i <= daysMar; i++)
        {
            $("#days").append("<p>" + "March " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "4")
    {
        $("#days").empty();
        for(i = 1; i <= daysApr; i++)
        {
            $("#days").append("<p>" + "April " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "5")
    {
        $("#days").empty();
        for(i = 1; i <= daysMay; i++)
        {
            $("#days").append("<p>" + "May " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "6")
    {
        $("#days").empty();
        for(i = 1; i <= daysJun; i++)
        {
            $("#days").append("<p>" + "June " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "7")
    {
        $("#days").empty();
        for(i = 1; i <= daysJul; i++)
        {
            $("#days").append("<p>" + "July " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "8")
    {
        $("#days").empty();
        for(i = 1; i <= daysAug; i++)
        {
            $("#days").append("<p>" + "August " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "9")
    {
        $("#days").empty();
        for(i = 1; i <= daysSep; i++)
        {
            $("#days").append("<p>" + "September " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "10")
    {
        $("#days").empty();
        for(i = 1; i <= daysOct; i++)
        {
            $("#days").append("<p>" + "October " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "11")
    {
        $("#days").empty();
        for(i = 1; i <= daysNov; i++)
        {
            $("#days").append("<p>" + "November " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    if(m === "12")
    {
        $("#days").empty();
        for(i = 1; i <= daysDec; i++)
        {
            $("#days").append("<p>" + "December " + i + "</p>");
            var newDiv = document.createElement('div');
            //creates an id by the day number
            newDiv.id = i.toString();
            $("#days").append(newDiv);
            
        }
    }
    });   
});