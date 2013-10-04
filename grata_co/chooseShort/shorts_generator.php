<?php

if (isset($_POST['short'])) { $short = $_POST['short']; }

if (isset($_POST['comment'])) { $comment = $_POST['comment']; }

if (isset($_POST['expire'])) { deleteFiles(); deleteFromDB(); header('Location: http://local-dev.chooseshort.com/shorts_generator'); }

if (isset($_POST['submit'])) { createShort($short, $comment); insertShort($short, $comment); header('Location: http://local-dev.chooseshort.com/shorts_generator'); }

firstForm();
#displayInfo();

/******************************************Display url shortener form****************************************/

function firstForm()
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

		<fieldset>
		<legend> Add Analytics and Browser Detection to a Short URL </legend> <br \>
			<strong> This form generates a php file with google analytics that redirects based on user agent </strong> <br \> <br \> <hr \>
			Flow [Example]: <br \>
			1.	Submit a custom short url "iluvbjs" <br \>
			2. 	Submit an identifying comment "Title IX" <br \>
			3. 	[Backend] Adds the file iluvbjs.php to the production server at /var/www/html/grata.co/ <br \>
			4.	[Backend] Adds a png file containing the qrcode linking to that shot url on the production server at /var/www/html/grata.co/QRCodes/ <br \>
			5.	Access the file via http://grata.co/iluvbjs <br \>
 			6.	See google analytics for that short url at page "Title IX" on the grata.co google analytics page
			7.	Re-generate the qrcode at grata.co/QRCodes/iluvbjs.png <br \> <hr \> <br \>
			
            <form class = "apply2" id = "upload" action = "upload.php" method = "post" enctype = "multipart/form-data">
                <label for = "upload"> Upload File: </label>
                <br />
				<input type = "file" name = "file" id = "file"/>
                <input type = "submit" name = "submit" value = "Upload File" />
            </form> <br />
			
			<form action = "" method = "post">
				<label for = "custom_short" > Type a custom short url:  </label>
				<input type = "text" size = "30" name = "short" value = "iluvbjs" id = "custom_short" /> <br \>
				<label for = "custom_comment" > Type a comment to label your url: </label>
				<input type = "text" size = "30" name = "comment" value = "Title IX" id = "custom_comment" /> <br \> <br \>
				<input type = "submit" name = "submit" value = "submit" />
			</form> <br \>
            
            <form action = "shorten.php" method = "post" id = "shorten" enctype="multipart/form-data"> 
                <label for = "urltext"> Type custom short: </label> <br />
                <input type = "text" name = "url" value = "<?php echo $name; ?>" style = "height:20px; width:240px;" id = "urltext">
				<input type = "submit" name = "insertDB" value = "Save URL" id = "save"> 
            </form> <br \>
            
            <form action = "" method = "post" id = "add" enctype="multipart/form-data">
                <label for = "add"> Add Attributes: </label> <br \>
				<input type = "text" name = "AttriVal" style = "width:80px; height:20px;"> <span> = </span>
                <input type = "text" name = "AttriVal" style = "width:80px; height:20px;">
                <input type = "button" name = "addAtts" id = "addAtts" value = "Add" /> 
                <input type = "button" name = "done" value = "Done" id = "done"/>
            </form> <br \>
        
        </fieldset>
	</body>
	</html>
	
	<?
}

/************ Display table "display" in database "grata_co_shorts" **************/

function displayInfo()
{
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui","grata_co_shorts") or die(mysqli_error());
	$sql = "SELECT * FROM display ORDER BY timestamp DESC LIMIT 10";
	$result = mysqli_query ($link, $sql) or die (mysql_error());  
	$path = "http://local-dev.chooseshort.com/QRCodes/";

	?>

	<!-- Display table "display" from database "grata_co_shorts" -->

	<html>
	<head></head>
	<body>
		<fieldset>
		<legend> Selected URLs </legend>
			<table>
				<tr> 
					<th> Timestamp </th> <td> &nbsp; </td> <td> &nbsp; </td>
					<th> Short URL </th> <td> &nbsp; </td> <td> &nbsp; </td>
					<th> QR Code </th> <td> &nbsp; </td> <td> &nbsp; </td>
					<th> Comment </th>
				</tr>

	<!-- Loop through table "display". Remove ".php" extension from short_url. Display qrcode, stored in grata.co/QRCodes  -->

			<?php while ($row = mysqli_fetch_array($result)) :?>
            	<tr>
                	<td> <?php echo $row['timestamp']; ?> </td> <td> &nbsp; </td> <td> &nbsp; </td> 
                	<td> <?php echo preg_replace('/\.[^.]*$/', '', $row['short_url']); ?> </td> <td> &nbsp; </td> <td> &nbsp; </td>
                	<td> <img src = "<?php echo $path.$row['qrcode']; ?>"> <?php $path.$row['qrcode']; ?> </td> <td> &nbsp; </td> <td> &nbsp; </td>
					<td> <?php echo $row['comment']; ?> </td>
					<td> 
					<form action = "" method = "post">
					<input type = "hidden" name = "expire" value = "<?php echo $row['short_url']; ?>" />
					<input type = "submit" value = "delete" />
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

/*

This function creates a php file at http://grata.co/short_url,
where the name of the short_url is based on what the user inputs
into the form. The php file, when run, will redirect
the user based on it's mobile browser agent to either
the apple play store, the android play store, or 
grata.com if not recognized. This file also sets up
google analytics at grata.co/comment (ID Code: UA-41229393-4),
where comment is based on what the user inputs into the form.

*/

function createShort($short, $comment)
  {
    $text = "<?php\n

    		/**** Google Analytics ****/

			include('/var/www/html/grata.co/php-ga-1.1.1/src/autoload.php');\n
			use UnitedPrototype\GoogleAnalytics;\n

			// Initialize GA Tracker\n
			\$tracker = new GoogleAnalytics\\Tracker('UA-41229393-4', 'www.grata.co');\n

			// Assemble Visitor information\n
			\$visitor = new GoogleAnalytics\\Visitor();\n
			\$visitor->setIpAddress(\$_SERVER['REMOTE_ADDR']);
			\$visitor->setUserAgent(\$_SERVER['HTTP_USER_AGENT']);

			// Assemble Session information\n
			\$session = new GoogleAnalytics\Session();\n

			// Assemble Page information\n
			\$page = new GoogleAnalytics\Page(\"/$short\");\n
			\$page->setTitle(\"$comment\");\n

			// Track page view\n
			\$tracker->trackPageview(\$page, \$session, \$visitor);\n

			/**** This calls the file download.php, which does mobile browser detection ****/

			include('/var/www/html/grata.co/download.php');\n
			detectAgent();";
    
    $fname = "/Users/iZhang/Desktop/Development/grata_co/chooseShort/".$short.".php";
    $fp = fopen($fname, 'w+') or die("Can't open file");
    $fwrite = fwrite($fp, $text);
    chmod($fname, 0775); 
  }

/****** 

Inserts the user inputted short url, a timestamp, 
a qr code (that links to grata.co/short_url),
and user inputted comment into table display, 
database grata_co_shorts  

******/

function insertShort($short, $comment) 
 { 
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui","grata_co_shorts") or die(mysqli_error());
    $root = "http://grata.co/";
	
    //generate qr code and save to filename shorturl.png
    include('/Users/iZhang/Desktop/Development/grata_co/admin/phpqrcode/phpqrcode.php');
    $fname = "/Users/iZhang/Desktop/Development/grata_co/chooseShort/QRCodes/".$short.".png";
    QRcode::png($root.$short, $fname); 
    chmod($fname, 0775);
    
    $qrcode = $short.'.png';
	$timestamp = date("m/d/y : H:i:s", time());
	$short = $short.".php";

    //insert values into table.
    $sql = "INSERT INTO display (timestamp, short_url, qrcode, comment)
            VALUES ('$timestamp', '$short', '$qrcode', '$comment')";
    mysqli_query($link, $sql) or die(mysqli_error());
 	mysqli_close($link);
 }

 /***************** Delete a given url (and associated shit) from grata.co/ and grata.co/QRCodes/  *******************/

function deleteFiles()
  {
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui", "grata_co_shorts") or die(mysqli_error());
    $id = $_POST['expire'];
    $sql = "select * from display where short_url = '$id'";
    $result = mysqli_query($link, $sql) or die(mysqli_error());
    $row = mysqli_fetch_array($result);
    $fPhp = $row['short_url'];
    $fPng = $row['qrcode'];
    $path = "/Users/iZhang/Desktop/Development/grata_co/chooseShort/";
    unlink($path.$fPhp);
    $path = "/Users/iZhang/Desktop/Development/grata_co/chooseShort/QRCodes/";
    unlink($path.$fPng);
    mysqli_close($link);
  }

/******* Deletes the information from the database, based on timestamp *********/

function deleteFromDB()
  {
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui", "grata_co_shorts") or die(mysqli_error());
    $id = $_POST['expire'];
    $sql = "delete from display where short_url = '$id'";
    mysqli_query($link, $sql) or die(mysqli_error());
    mysqli_close($link);
  }
