$(document).ready(function()
    { 
$("#addAtts").click(function()
        {
            $("#add").append("<br><input type = 'text' style = 'height:20px; width:80px;' name = 'AttriVal'> <span> = </span> <input type = 'text' style = 'height:20px; width:80px;' name = 'AttriVal'><br>");
        });
        $("#done").click(function()
        {    
query = $("#add").serialize(); 
            $("#urltext").val($("#urltext").val() + query);
        });
    });
