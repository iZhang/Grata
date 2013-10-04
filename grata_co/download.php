<?php

include('/Users/iZhang/Desktop/Development/grata_co/php-ga-1.1.1/src/autoload.php');
use UnitedPrototype\GoogleAnalytics;

// Initialize GA Tracker
$tracker = new GoogleAnalytics\Tracker('UA-41229393-4', 'www.grata.co');

// Assemble Visitor information
$visitor = new GoogleAnalytics\Visitor();
$visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);

// Assemble Session information
$session = new GoogleAnalytics\Session();

// Assemble Page information
$page = new GoogleAnalytics\Page('/download');
$page->setTitle('Downloads');

// Track page view
$tracker->trackPageview($page, $session, $visitor);

detectAgent();

/***********Detect Browser based off keywords in User Agent*************/

function detectAgent()
  {
	$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
	$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$ipad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
		
	if ($android || $palmpre || $berry == true) 
	{ 
		$long = "https://play.google.com/store/apps/details?id=com.guestpass.app";
		//genshort
		$shorten = geturl($long);
		$short = trimString($shorten);
		//save file
		genFile($long, $short);
		//enter into database
		insertDB($long, $short);
		//clickanalytics??		
		clickanalytics($short);
		header("Location: $long"); 
	} 
	else if ($iphone || $ipod || $ipad == true)
	{
		$long = "https://itunes.apple.com/us/app/guestops/id582206023";
		//genshort
		$shorten = geturl($long);
		$short = trimString($shorten);
		//save file
		genFile($long, $short);
		//enter into database
		insertDB($long, $short);
		//clickanalytics??		
		clickanalytics($short);
		header("Location: $long"); 
	}
	else
	{ header('Location: http://www.grata.com'); } 
  }

/************The following 4 functions convert a longurl into a shorturl**************/

function dbconnect()
  {
    $link = mysql_pconnect("localhost", "root", "s3ns89ui") or die(mysql_error());
    $db_selected = mysql_select_db("analytics", $link) or die(mysql_error());
  }

function generateshort($numAlpha = 6)
  {
    dbconnect();
    $listAlpha = 'abcdefghijklmnopqrstuvwxyz0123456789';
    return str_shuffle(substr(str_shuffle($listAlpha),0,$numAlpha));
  }

function get_Domain ($long)
  {
    $url = trim($long);
    $parse = parse_url($url);
    return $parse['host'];
  }
function geturl($in)
  {
    dbconnect();

    do
    {
        $out = generateshort();
        $query = "SELECT short_url  FROM `urlanalysis` WHERE `short_url` = '$out'";
        $result = mysql_query($query);
    } while (mysql_num_rows > 1);

    $domain = get_Domain($in);

    return "$domain"."/"."$out";
  }

/******Store file in server path /var/www/html/grata.co/QRChange/Tracks******/

function genFile($long, $short)
  { 
    $text = "<?php\n
	    header(\"Cache-Control: no-cache, must-revalidate\");\n
	    header(\"Expires: Thu, 1 Jan 1970 00:00:00 GMT\");\n
	    header(\"Status: 301 Moved Permanently\");\n
	    header(\"Location: $long\");\n
	    ignore_user_abort(true);";
    $fname = "/var/www/html/grata.co/s/".$short.".php";
    $fp = fopen($fname, 'w+') or die("can't open file");
    $fwrite = fwrite($fp, $text);
    chmod($fname, 0755); 
  }

function trimString($url)
  {
	$shortened = geturl($url);	
	$request = parse_url($shortened);
	$path = $request["path"];
	$result = explode('/', $path);
	return $result[1];
  }

/********insert url info********/

function insertDB($long, $short)
    //first table contains urlID :url, attributes, short url, qr code --url info
 {
    dbconnect();
    $short = $short.".php";
    $qr = "qr";
    //insert form values into table.
    $sql = "INSERT INTO urlanalysis (url, short_url, attriVal, attriID, qr_code)
            VALUES ('$long','$short', 'NULL', 'NULL', '$qr')";
    mysql_query($sql) or die(mysql_error());
 }

function clickanalytics($file) 
 {
    dbconnect();
    //second table contains shorturl, timestamp, user agent --click info 
     
    $short = "$file".".php";
    $tstamp = date("m/d/y : H:i:s", time());
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    
    $sql = "INSERT INTO tracks (timestamp, user_agent, short_url)
    VALUES ('$tstamp','$uagent','$short')";

    mysql_query($sql) or die(mysql_error());
 }
