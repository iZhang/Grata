<?php

if (isset($_POST['short'])) { $short = $_POST['short']; }

if (isset($_POST['comment'])) { $comment = $_POST['comment']; }

if (isset($_POST['expire'])) { deleteFiles(); deleteFromDB(); header('Location: http://local-dev.chooseshort.com/'); }

if (isset($_POST['submit'])) { createShort($short, $comment); insertShort($short, $comment); header('Location: http://local-dev.chooseshort.com/'); }

form();
displayInfo();

/*************Present Form************/

function form()
{
	?>

	<html>
	<head> <title> Customize a Short URL for grata.co/ </title> </head>
	<body>

	<!-- Display Form -->

		<fieldset>
		<legend> Add Analytics to a Short URL </legend>
			<br \>
			<strong> This form generates a php file with google analytics that redirects based on user agent </strong> <br \> <br \>
			Example: <br \>
			1.	Submit a custom short url "iluvbjs" <br \>
			2. 	[Backend] Adds the file iluvbjs.php to the production server at /var/www/html/grata.co/ <br \>
			3.	Access the file via http://grata.co/iluvbjs
			<br \>
			<br \>
			<form action = "" method = "post">
				<input type = "text" size = "30" name = "short" value = "submit custom short" />
				<input type = "text" size = "50" name = "comment" value = "submit comment with short" />
				<br \>
				<input type = "submit" name = "submit" value = "submit" />
			</form>
		</fieldset>

	</body>
	</html>
	
	<?
}

/************ Display table "display" in database "shorts" **************/

function displayInfo()
{
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui","shorts") or die(mysqli_error());
	$sql = "SELECT * FROM display ORDER BY timestamp DESC LIMIT 10";
	$result = mysqli_query ($link, $sql) or die (mysql_error());  
	$path = "http://local-dev.chooseshort.com/";

	?>

	<!-- Display info -->

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

/********* The following two functions process Form Data ***********/

function createShort($short, $comment)
  {
    $text = "<?php\n
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

			include('/var/www/html/grata.co/download.php');\n
			detectAgent();";
    
	$fname = "/Users/iZhang/Desktop/Development/grata_co/chooseShort/".$short.".php";
    $fp = fopen($fname, 'w+') or die("Can't open file");
    $fwrite = fwrite($fp, $text);
    chmod($fname, 0775); 
  }

function insertShort($short, $comment) 
 { 
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui","shorts") or die(mysqli_error());
    $root = "http://local-dev.chooseshort.com";
	
    //generate qr code and save to filename shorturl.png
    include('/Users/iZhang/Desktop/Development/grata_co/admin/phpqrcode/phpqrcode.php');
    $fname = "/Users/iZhang/Desktop/Development/grata_co/chooseShort/".$short.".png";
    QRcode::png($root."/".$short.".php", $fname); 
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

 /***************** Delete a given url (and associated shit) in the database and in the table *******************/

function deleteFiles()
  {
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui", "shorts") or die(mysqli_error());
    $id = $_POST['expire'];
    $sql = "select * from display where short_url = '$id'";
    $result = mysqli_query($link, $sql) or die(mysqli_error());
    $row = mysqli_fetch_array($result);
    $fPhp = $row['short_url'];
    $fPng = $row['qrcode'];
    $path = "/Users/iZhang/Desktop/Development/grata_co/chooseShort/";
    unlink($path.$fPhp);
    unlink($path.$fPng);
    mysqli_close($link);
  }

function deleteFromDB()
  {
    $link = mysqli_connect("local-dev.chooseshort.com", "root", "s3ns89ui", "shorts") or die(mysqli_error());
    $id = $_POST['expire'];
    $sql = "delete from display where short_url = '$id'";
    mysqli_query($link, $sql) or die(mysqli_error());
    mysqli_close($link);
  }
