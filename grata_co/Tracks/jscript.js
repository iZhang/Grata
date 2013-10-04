$(document).ready(function()
{ 
    $("#addAtts").click(function()
    {
        $("#add").append("<br><textarea cols = '10' rows = '1' name = 'AttriVal' id = 'val'></textarea> <span> = </span> <textarea type = 'text' cols = '10' rows = '1' name = 'AttriID' id = 'ID'></textarea><br>");
    });
    $("#upload").click(function()
    {
        var root = <?php echo json_encode($root.'/'); ?>;
        $("#urltext").val(root += $("#file").val());
    });
    $("#save").submit(function()
    {
        $("#add").ajaxForm({url: "analytics.php", type: "post"});
    });
});

