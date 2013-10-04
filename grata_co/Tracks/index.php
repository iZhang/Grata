<?php

if (isset($_POST['expire']))
    { expire(); }
else if (isset($_POST['upload']))
    { upload(); }
else if (isset($_POST['done']))
    { fixAtts(); }
else
    { urlform(); }

displayurlinfo();

/**********connect to database***********/

function dbconnect()
  {
    $link = mysql_pconnect("localhost", "root", "root");
    $db_selected = mysql_select_db("analytics", $link);
  }

/******************************************Display url shortener form****************************************/

function urlform()
  {
    //display regular form 
        ?>

        <html>
        <head>
            <title> Short URL Generator </title>  
        
            <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
            <script type = "text/javascript">
                $(document).ready(function()
                { 
                    $("#addAtts").click(function()
                    {
                        $("#add").append("<br><textarea cols = '10' rows = '1' name = 'AttriVal'></textarea> <span> = </span> <textarea type = 'text' cols = '10' rows = '1' name = 'AttriVal'></textarea><br>");
                    });
                    $("#done").click(function()
                    {    
                        query = $("#add").serialize(); 
                        $("#urltext").val($("#urltext").val() + query);
                    });
                });
            </script>
            <style type = "text/css">
                #onerow
                    { display: inline; }
            </style>
        </head>
        <body>
            <fieldset>
            <legend> New URL </legend> 
            <form action = "shorten.php" method = "post" id = "onerow">
                <label for = "urltext"> Original Domain: </label>
                <textarea name = "url" cols = "40" rows = "1" id = "urltext"></textarea>
                <input type = "submit" name = "insertDB" value = "Save URL" id = "save"> <span> or </span> 
            </form>
            <form action = "upload.php" method = "post" enctype = "multipart/form-data">
                <input type = "file" name = "upload"/>
                <input type = "submit" value = "Upload File" />
            </form>
            </div>
            <br />
            <br />
             <label for = "val"> Attributes: </label>
            <br />
            <p>
            <form action = "" method = "post" id = "add">
                <textarea name = "AttriVal" cols = "10" rows = "1"></textarea> <span> = </span>
                <textarea name = "AttriVal" cols = "10" rows = "1"></textarea>
                <input type = "button" name = "addAtts" id = "addAtts" value = "Add Attributes" />    
                <input type = "button" name = "done" value = "Done" id = "done"/>
            </form>
            </p>
        </body>
        </fieldset>
        
        </html>
        <?     
  }

/******Add Attributes to domain name******/

function fixAtts()
  {
    $items = $_POST['done'];
    $sb = "";
    foreach ($_POST as $key => $value)
    {
        $count = count($value);
        for ($i = 0; $i < $count-1; $i+=2)
            { $sb .= '&'.$value[$i].'='.$value[$i+1]; }
    }
    
    return $sb;
  }

/*****Allow User to Upload File to Directory*****/

function upload()
  {
    echo "got here";

    $userFile = $_FILES['upload']['name'];
    echo $userFile;
    $target_path = "http://192.168.2.116:8888/alex_project_files/";
    $target_path = $target_path.basename($_FILES['upload']['name']);
    move_uploaded_file($_FILES['upload']['tmp_name'], $targetPath);
    if(move_uploaded_file($_FILES['upload']['tmp_name'], $targetPath))
        { echo "The file".basename($_FILES["$userFile"]['name'])." has been uploaded"; }
    else
        { echo "Error uploading file, please try again"; }
  }

/***********Delete Files From Directory**********/

function expire()
  {
    dbconnect();
    $id = $_POST['expire'];
    $sql = "SELECT FROM urlanalysis WHERE id = \"$id\"";
    $result = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_array($result) or die(mysql_error()); 
    $fDeletePhp = $row['short_url'];
    $fDeletePng = $row['qr_code'];
    unlink("alex_project_files".$fDeletePhp);
    unlink("$fDeletePng");
  }

/***************************************Display updated url info************************************/

function displayurlinfo()
 { 
    dbconnect();
    $result = mysql_query('select * from urlanalysis');
    $path = "http://192.168.2.116:8888/alex_project_files/";
            ?>
   
    <fieldset>   
      <table border = '0' cellpadding = '0' >
        <legend> My URLs </legend>
        <tr>
            <th> URL </th>
            <td> &nbsp; </td>
            <th> Short Url </th>
            <td> &nbsp; </td>
            <td> &nbsp; </td>
            <th> qr_code </th>
        </tr>
       <?php while ($row = mysql_fetch_array($result)) :?>
            <tr>
                <td> <a href = "http://<?php echo $row['url']; ?>"> <?php echo $row['url'] ?> </a> </td> 
                <td> &nbsp; </td>
                <td> <a href = "<?php echo $path.$row['short_url']; ?>"> <?php echo $path; ?><?php echo $row['short_url']; ?> </a> </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> <img src = "<?php echo $row['qr_code']; ?>"><?php $row['short_url']; ?></td>
                <td> 
                <form action = "" method = "post" > 
                <input type = "hidden" name = "expire" value = "<?php echo $row['id']; ?>" />
                <input type = "submit" value = "Expire" /> 
                <label for = "clicks">Clicks:</label>
                <textarea cols = "2" rows = "2" id = "clicks" name = "clicks"><?php echo "3"; ?></textarea>
                </form>
                </td> 
            </tr>     
        <?php endwhile; ?>
    </table>
   
    </fieldset>    
   
   <?
    
 } 

function clickanalytics($file)
 
 {
    dbconnect();
    $result = mysql_query("select * from tracks");
    
    //second table contains shorturl, timestamp, user agent --click info 
    
    $short = $file.'.php';
    $tstamp = date("m/d/y : H:i:s", time());
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    
    $sql = "INSERT INTO tracks (timestamp, user_agent, short_url )
    VALUES ('$tstamp','$uagent','$short')";

    mysql_query($sql) or die(mysql_error());
 }

