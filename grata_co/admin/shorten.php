<?php

if (isset($_POST['url']))
    {    
		$longurl = $_POST['url'];
		$shorten = geturl($longurl);
		$parts = explode('/', rtrim($shorten, '/'));
		$short = $parts[1];
    }
else
    { die(); }

if (isset($_POST['insertDB']))
    { 
        addurlinfo();
        createFile($longurl);
    } 
else
    { die(); }

header('Location: http://www.grata.co/admin/index',TRUE,302);

function dbconnect()
  {
    $link = mysql_pconnect("localhost", "root", "root") or die(mysql_error());
    $db_selected = mysql_select_db("analytics", $link) or die(mysql_error());
  }

function createFile($name)
  {
    global $longurl, $short;
    $text = "<?php\n
            header(\"Cache-Control: no-cache, must-revalidate\");\n
            header(\"Expires: Thu, 1 Jan 1970 00:00:00 GMT\");\n
            header(\"Status: 301 Moved Permanently\");\n
            header(\"Location: $longurl\");\n
            ignore_user_abort(true);\n
            include('/Users/iZhang/Desktop/Development/grata_co/admin/index.php');\n 
            clickanalytics('$short');";
    $directory = "../s/".$short.".php";
    $fp = fopen($directory, 'w+') or die("can't open file");
    $fwrite = fwrite($fp, $text);
    chmod($directory, 0777); 
  }

/********insert url info********/

function addurlinfo() 
    //first table contains urlID :url, attributes, short url, qr code --url info
 { 
    dbconnect(); 
    global $longurl, $short;
    
    $link = "www.grata.co/s";
    $path = "../s";
    //$attriVal = $_POST['AttriVal'];
    //$attriID = $_POST['AttriID'];
    
    //generate qr code and save to filename shorturl.png
    include('/Users/iZhang/Desktop/Development/grata_co/admin/phpqrcode/phpqrcode.php');
    $fname = "$path"."/"."$short".".png"; 
    QRcode::png("$link"."/"."$short", $fname); 
    $qrcode = "$short".".png";
    $shorturl = $short.'.php';

    //insert form values into table.
    $sql = "INSERT INTO urlanalysis (url, short_url, attriVal, attriID, qr_code)
            VALUES ('$longurl','$shorturl', 'NULL', 'NULL', '$qrcode')";
    mysql_query($sql) or die(mysql_error());
 }

/********generate short query string********/

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
    } while ($row = mysql_fetch_assoc($result));

    $domain = get_Domain($in);

    return "$domain".'/'."$out";
  }
