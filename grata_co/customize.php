<?php

if (isset($_POST['action']))
	{
		if (isset($_POST['short']))
		{ $short = $_POST['short']; }
		if (isset($_POST['comment']))
		{ $comment = $_POST['comment']; }

		createShort($short, $comment); 
		insertDB($short, $comment);
	}
if (isset($_POST['expire']))
    { expire(); }
if (isset($_POST['deleteTrack']))
    { deleteTrack(); }

displayFlow();
displayUrlInfo();
#displayTracks();

/*

Display all forms and workflow

*/

function displayFlow()
	{
		session_start();
		if (isset($_SESSION['name']))
    		{ $name = $_SESSION['name']; }
		else
    		{ $name = ""; }

		?>

		<html>
		<head> <title> Customize a Short Url </title> 

			<!-- Javascript -->
			
			<script src = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script><script type = "text/javascript"></script>
			<script src = "http://local-dev.customshort.com/grata_co_shorts.js"> <script type = "text/javascript"></script>

			<!-- CSS -->

			<link rel = "stylesheet" type = "text/css" href = "grata_co_shorts.css" />

		</head>
		<body>
			<div class = "center" >

				<fieldset class = "fieldset-auto-width"> 
				<legend> Flow </legend>
					http://grata.co/customize is an interface that allows a user to
					generate a unique short url on the production server. This short <br \>
					url either redirects to an uploaded file (selected by the user)
					i.e., grata.co/gcmh == Grata Content Marketing Handbook) or <br \> 
					represents a Grata-partner brand, i.e. grata.co/pbj == Peninsula
					Beijing. <br \>
				</fieldset> <br \> <br \>
				
				<fieldset class = "fieldset-auto-width">
					If you'd like to upload a file: <br \> <img src = "http://local-dev.customshort.com/uploads/left_down_arrow.png" class = "resize" />
				</fieldset> &nbsp; &nbsp; &nbsp; &nbsp;

				<fieldset class = "fieldset-auto-width">
					Create a short url directly: <br \> <img src = "http://local-dev.customshort.com/uploads/right_down_arrow.png" class = "resize" />
				</fieldset>	<br \> <br \> <br \>
				
				<fieldset class = "fieldset-auto-width" style = "top:275px; left:300px">
					<legend> Upload a File to the Production Server </legend> <br \>
		 			<form action = "upload.php" method = "post" enctype = "multipart/form-data"> 
                		<input type = "file" name = "file" id = "file"/> <br \> <br \>
                		<input type = "submit" name = "submit" value = "Upload File" />
            		</form> <br />		
				</fieldset>

				<img src = "http://local-dev.customshort.com/uploads/right_arrow.png" class = "resize" />

				<fieldset class = "fieldset-auto-width" style = "top:275px; right:200px">
					<legend> Create a Unique Short Url </legend> <br \>
            		<form  action = "" method = "post" id = "shorten" enctype = "multipart/form-data"> 
                		<label for = "urltext"> Your Desired Short URL: </label> <br \> <br \>
                		<input type = "text" name = "short" value = "" style = "height:20px; width:150px;" id = "custom_short"> <br \> <br \>
                		<label for = "custom_comment" > Comment/Label: </label> <br \> <br \>
						<input type = "text" name = "comment" value = "<?php echo $name; ?>" id = "custom_comment" style = "height:20x; width:150px;" /> <br \> <br \>
						<label for = "custom_long" > Select Final Destination <br \> (optional): </label> <br \> <br \>
						<input type = "text" name = "long" value = "" id = "custom_long" style = "height:20px; width:200px;" /> <br \> <br \>
						<input type = "checkbox" name = "google_detect"/> Add Google Analytics and Browser Detection to File <br \> <br \>
						<input type = "submit" name = "action" value = "Action!" /> <br \>
					</form>
				</fieldset>
			
			</div>
		<br \> <br \> <br \>
		</body>
		</html>
		<?
		session_destroy();
	}

/*

This function creates a php file at http://grata.co/short_url,
where the name of the short_url is based on what the user inputs
into the form. The php file, when run, will redirect
the user based on it's mobile browser agent to either
the apple play store, allow the user to select an android play 
store, or grata.com if not recognized. This file also sets up
google analytics at grata.co/comment (ID Code: UA-41229393-4),
where comment is based on what the user inputs into the form.

*/

function createShort($short, $comment)
  {
    $analyze = "<?php\n
			
    		/**** Google Analytics ****/\n

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
			
			/**** This calls the file download.php, which does mobile browser detection ****/\n

			include('/var/www/html/grata.co/download.php');\n
			detectAgent();";
   
   	if (isset($_POST['long']))
   		{ $longurl = $_POST['long']; }
   	else 
   		{ $longurl = ""; }
	$redirect = "<?php\n
            header(\"Cache-Control: no-cache, must-revalidate\");\n
            header(\"Expires: Thu, 1 Jan 1970 00:00:00 GMT\");\n
            header(\"Status: 301 Moved Permanently\");\n
            header(\"Location: $longurl\");\n
            ignore_user_abort(true);\n
            include('/Users/iZhang/Desktop/Development/grata_co/customize.php');\n 
            clickAnalytics('$short');";	

    $fname = "/Users/iZhang/Desktop/Development/grata_co/".$short.".php";
    
	// ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $fname);

    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo('Users/iZhang/Desktop/Development/grata_co/' . $name);
    while (file_exists($name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }
    
	$fp = fopen($name, 'w+') or die("Can't open file");

    if (isset($_POST['google_detect']))
	{ 
		$fwrite = fwrite($fp, $analyze);
    	chmod($name, 0775); 
	}
	else
	{
		$fwrite = fwrite($fp, $redirect);
		chmod($name, 0775);
	}
  }

/* 

Inserts the user inputted short url, a timestamp, 
a qr code (that links to grata.co/short_url),
and user inputted comment into table display, 
database grata_co_shorts  

*/

function insertDB($short, $comment) 
 { 
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui","grata_co_shorts") or die(mysqli_error());
    $root = "http://local-dev.customshort.com/";
	
    //generate qr code and save to filename shorturl.png
    include('/Users/iZhang/Desktop/Development/grata_co/admin/phpqrcode/phpqrcode.php');
    $fname = "/Users/iZhang/Desktop/Development/grata_co/QRCodes/".$short.".png";
    QRcode::png($root.$short, $fname); 
    chmod($fname, 0775);

    $qrcode = $short.'.png';
	$short = $short.".php";
	if (isset($_POST['long']))
		{ $longurl = $_POST['long']; }
	else
		{ $longurl = ""; }

    //insert values into table.
    $sql = "INSERT INTO display (long_url, short_url, qrcode, comment)
            VALUES ('$longurl', '$short', '$qrcode', '$comment')";
    mysqli_query($link, $sql) or die(mysqli_error());
 	mysqli_close($link);
 }

/***************************************Table 'display'************************************/

function displayUrlInfo()
 {
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui","grata_co_shorts") or die(mysqli_error());
    $sql = "SELECT * FROM display ORDER BY id DESC LIMIT 10";
    $result = mysqli_query ($link, $sql) or die (mysqli_error());
    $path = "http://local-dev.customshort.com/";
 ?>
    <html>
    <body>
    <fieldset id = "table">
      <table border = '0' cellpadding = '0' >
        <legend> Shorts Generated </legend>
        <tr>
            <th> URL </th> <td> &nbsp; </td>
            <th> Short Url </th> <td> &nbsp; </td> <td> &nbsp; </td>
            <th> QR Code </th> <td> &nbsp; </td> <td> &nbsp; </td>
			<th> Comment </td> <td> &nbsp; </td>
        </tr>
       <?php while ($row = mysqli_fetch_array($result)) :?>
            <tr>
                <td> <a href = "<?php echo $row['long_url']; ?>"> <?php echo $row['long_url'] ?> </a> </td> <td> &nbsp; </td>
                <td> <a href = "<?php echo $path.$row['short_url']; ?>"> <?php echo 'grata.co/'.preg_replace('/\.[^.]*$/', '', $row['short_url']); ?> </a> </td> <td> &nbsp; </td> <td> &nbsp; </td>
                <td> <img src = "<?php echo $path.'QRCodes/'.$row['qrcode']; ?>"><?php $path.'QRCodes/'.$row['qrcode']; ?></td> <td> &nbsp; </td> <td> &nbsp; </td>
                <td> <?php echo $row['comment']; ?> </td> <td> &nbsp; </td> <td> &nbsp; </td>
	
				<!--
				<td> 
                	<label for = "clicks">Clicks:</label>
                	<input type = "text" id = "clicks" name = "clicks" value = "<?php echo getCount($row['short_url']); ?>" style = "height:20px; width:30px" \>
                	<form action = "" method = "post" > 
				</td>
				-->

				<td>
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

/*******************display table with analytics/tracking information*******************/

function displayTracks()
  {
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui","grata_co_shorts") or die(mysqli_error());
    $result = mysqli_query($link, 'select * from tracks order by timestamp desc limit 10');
    
	?>

    <html>
    <body>
    <fieldset>
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
       <?php while ($row = mysqli_fetch_array($result)) :?>
            <tr>
                <td> <?php echo $row['timestamp']; ?> </td>
                <td> &nbsp; </td>
                <td> <?php echo $row['user_agent']; ?> </td>
                <td> &nbsp; </td>
                <td> &nbsp; </td>
                <td> <?php echo $row['short_url']; ?> </td>
            <td>
        <form action = "" method = "post" class = "right">
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

/*****Count # of Entries of ShortUrl in DB analytics, table tracks*****/

function getCount($fName)
 {
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui","grata_co_shorts") or die(mysqli_error());
    $result = mysqli_query($link, "select sum(short_url = '$fName') as sum from tracks") or die(mysqli_error());
    $row = mysqli_fetch_assoc($result);
    return $row['sum'];    
 }

/***********Delete Files From Directory**********/

function expire()
  {  
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui", "grata_co_shorts") or die(mysqli_error());
    $id = $_POST['expire'];
    $sql = "select * from display where id = '$id'";
    $result = mysqli_query($link, $sql) or die(mysqli_error());
    $row = mysqli_fetch_array($result);
    $fPhp = $row['short_url'];
    $fPng = $row['qrcode'];
    $path = "/Users/iZhang/Desktop/Development/grata_co/";
    unlink($path.$fPhp);
    unlink($path.$fPng);  
  }

function deleteTrack()
  {
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui", "grata_co_shorts") or die(mysqli_error());
    $id = $_POST['deleteTrack'];
    $sql = "delete from tracks where short_url = '$id'";
    mysqli_query($link, $sql) or die(mysqli_error());
  }

function deleteFromDB()
  {
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui", "grata_co_shorts") or die(mysqli_error());
    $id = $_POST['expire'];
    $sql = "delete from display where id = '$id'";
    $result = mysqli_query($link, $sql) or die(mysqli_error());
  }

/*****Afer User clicks, shorturl, add info to DB analytics, table tracks*****/

function clickAnalytics($short) 
 {
    $link = mysqli_connect("local-dev.customshort.com", "root", "s3ns89ui", "grata_co_shorts") or die(mysqli_error());
     
    $short = $file.".php";
    $tstamp = date("m/d/y : H:i:s", time());
    if ($_SERVER['HTTP_USER_AGENT'])
	{ $uagent = $_SERVER['HTTP_USER_AGENT']; }
    
    $sql = "INSERT INTO tracks (timestamp, user_agent, short_url)
    VALUES ('$tstamp','$uagent','$short')";

    mysqli_query($link, $sql) or die(mysqli_error());
 } 
