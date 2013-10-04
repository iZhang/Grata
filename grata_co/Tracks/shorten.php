<?php

include('index.php');

$longurl = $_POST['url'];
$shorten = geturl($longurl);
$get = preg_replace("/^.*\//","",$shorten);
$parts = explode('/', rtrim($shorten, '/'));
$domain = get_Domain($longurl);
$short = $parts[1];

if (isset($_POST['insertDB']))
    { 
        addurlinfo();
        createFile($longurl);
    } 
else
    { echo "failure"; }

function createFile($name)
  {
    global $longurl, $short;

    $text = "<?php\n
            header(\"Cache-Control: no-cache, must-revalidate\");\n
            header(\"Expires: Thu, 1 Jan 1970 00:00:00 GMT\");\n
            header(\"Status: 301 Moved Permanently\");\n
            header(\"Location: http://$longurl\");\n
            ignore_user_abort(true);\n
            include('index.php');\n 
            clickanalytics(\"$short\");";
    $fname = "$short".".php";
    $fp = fopen($fname, 'w+') or die("can't open file");
    $fwrite = fwrite($fp, $text); 
  }

/********insert url info********/

function addurlinfo() 
    //first table contains urlID :url, attributes, short url, qr code --url info
 { 
    dbconnect(); 
    global $longurl, $short, $domain;
    
    $path = "http://192.168.2.116:8888/alex_project_files";
    $attriVal = $_POST['AttriVal'];
    $attriID = $_POST['AttriID'];
    
    //generate qr code and save to filename shorturl.png
    include('/Users/iZhang/Downloads/phpqrcode/phpqrcode.php');
    $fname = "$short".".png"; 
    QRcode::png($path.'/'.$short.'.php', $fname);
    $qrcode = $path.'/'.$fname;
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
    //$url = preg_replace("/^(http:\/\/)*(www.)*/is", "", $url);
    $url = preg_replace("/\/.*$/is" , "" ,$url);
    return $url;
  }

function geturl($in)
  {
    dbconnect();
    
    do
    {
        $out = generateshort();
        $query = "SELECT short_url  FROM `tracks` WHERE `short_url` = '$out'";
        $result = mysql_query($query);
    } while (mysql_num_rows > 1);

    $domain = get_Domain($in);

    return "$domain".'/'."$out";
  }
