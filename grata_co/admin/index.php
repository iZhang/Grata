<?php

form();
displayurlinfo();
displaytracks();

/**********connect to database***********/

function dbconnect()
  {
    $link = mysql_pconnect("localhost", "root", "root") or die(mysql_error());
    $db_selected = mysql_select_db("analytics", $link) or die(mysql_error());
  }

/******************************************Display url shortener form****************************************/

function form()
  {
	session_start();
	if (isset($_SESSION['name']))
	    { $name = '/Users/iZhang/Desktop/Development/grata_co/uploads/'.$_SESSION['name']; }
	else
	    { $name = ""; }
    
	//display regular form
        
	?>
        <html>
        <head>
            <title> Short URL Generator </title> 
        
            <script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
            <script type = "text/javascript">

	/*************Javascript***************/
               
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
            </script>

	<!-- CSS -->

	<link rel = "stylesheet" type = "text/css" href = "style.css">
        
	</head>
        <body>
            <fieldset id = "table";>
            <legend> New URL </legend> 
	    <fieldset id = "urlB">
            <form  action = "shorten.php" method = "post" id = "shorten" enctype="multipart/form-data"> 
                <label for = "urltext"> Type URL (type in full URL including scheme, i.e. http://www.grata.co/path): </label>
	    	<br />
                <input type = "text" name = "url" value = "<?php echo $name; ?>" style = "height:20px; width:240px;" id = "urltext">
		<input type = "submit" name = "insertDB" value = "Save URL" id = "save"> 
            </form>
            <form action = "addAtts.php" method = "post" id = "add" enctype="multipart/form-data">
                <label for = "add"> Add Attributes: </label>
                <br />
		<input type = "text" name = "AttriVal" style = "width:80px; height:20px;"> <span> = </span>
                <input type = "text" name = "AttriVal" style = "width:80px; height:20px;">
                <input type = "button" name = "addAtts" id = "addAtts" value = "Add" /> 
                <input type = "button" name = "done" value = "Done" id = "done"/>
            </form>
	    </fieldset>
            <br />
            <form class = "apply2" id = "upload" action = "upload.php" method = "post" enctype = "multipart/form-data">
                <label for = "upload"> Upload File: </label>
                <br />
		<input type = "file" name = "file" id = "file"/>
                <input type = "submit" name = "submit" value = "Upload File" />
            </form>
            <br />
        </fieldset>
        </body>
	</html>
        <?     
	session_destroy();
  }

/*******************display table with analytics/tracking information*******************/

function displaytracks()
  {
    dbconnect();
    $result = mysql_query('select * from tracks order by timestamp desc limit 10');
	?>
    <html>
    <body>
    <fieldset id = "table"> 
      <table border = '0' cellpadding = '0' >
        <legend> My URLs </legend>
        <tr>
            <th> Timestamp </th>
            <td> &nbsp; </td>
            <th> User agent </th>
            <td> &nbsp; </td>
            <td> &nbsp; </td>
            <th> Short url </th>
        </tr>
       <?php while ($row = mysql_fetch_array($result)) :?>
            <tr>
                <td> <?php echo $row['timestamp']; ?> </td> 
                <td> &nbsp; </td>
                <td> <?php echo $row['user_agent']; ?> </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> <?php echo $row['short_url']; ?> </td>
           	<td> 
		<form action = "delete.php" method = "post" class = "right">
		<input type = "hidden" name = "deleteTrack" value = "<?php echo $row['short_url']; ?>" />
		<input type = "submit" value = "Delete Track" />
		</form> 
            </tr> 
        <?php endwhile; ?>
    </table>
   
    </fieldset>
    </body>
    </html> 
	<?
  }

/***************************************Display table urlanalysis************************************/

function displayurlinfo()
 { 
    dbconnect();

    if (isset($_GET["page"])) 
	{ $page  = $_GET["page"]; } 
    else { $page = 1; };
    $start_from = ($page - 1) * 10;
    $sql = "SELECT * FROM urlanalysis ORDER BY id DESC LIMIT $start_from, 10";
    $result = mysql_query ($sql) or die (mysql_error());  
    $path = "/Users/iZhang/Desktop/Development/grata_co/";
	?>
    <html>
    <body>
    <fieldset id = "table">
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
                <td> <a href = "<?php echo $row['url']; ?>"> <?php echo $row['url'] ?> </a> </td> 
                <td> &nbsp; </td>
                <td> <a href = "<?php echo $path."s/".$row['short_url']; ?>"> <?php echo 'grata.co/s/'.preg_replace('/\.[^.]*$/', '', $row['short_url']); ?> </a> </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> <img src = "<?php echo $path."s/".$row['qr_code']; ?>"><?php $path."s/".$row['qr_code']; ?></td>
                <td> 
                <label for = "clicks">Clicks:</label>
                <textarea cols = "2" rows = "1" id = "clicks" name = "clicks"><?php echo getCount($row['short_url']); ?></textarea>
                <form class = "right" action = "delete.php" method = "post" enctype = "multipart/form-data"> 
                <input type = "hidden" name = "expire" value = "<?php echo $row['id']; ?>"/>
		<input type = "submit" value = "Expire" />
		</form> 
                </td> 
            </tr> 
        <?php endwhile; ?>
    </table> 
    </fieldset> 
    </body>
    </html> 
  <?
  }

/*****Count # of Entries of ShortUrl in DB analytics, table tracks*****/

function getCount($fName)
 {
    dbconnect();
    $result = mysql_query("select sum(short_url = '$fName') as sum from tracks") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row['sum'];    
 }

/*****Afer User clicks, shorturl, add info to DB analytics, table tracks*****/

function clickanalytics($file) 
 {
    dbconnect();
    //second table contains shorturl, timestamp, user agent --click info 
     
    $short = $file.".php";
    $tstamp = date("m/d/y : H:i:s", time());
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    
    $sql = "INSERT INTO tracks (timestamp, user_agent, short_url)
    VALUES ('$tstamp','$uagent','$short')";

    mysql_query($sql) or die(mysql_error());
 }
?>
