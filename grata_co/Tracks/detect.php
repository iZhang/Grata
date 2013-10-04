<?php
//short url to app store for their respective phone

$uAgent = $_SERVER['HTTP_USER_AGENT'];

$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$ipad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");

$longgoog = 'https://play.google.com/store/apps/details?id=com.guestpass.app';
$longapple = 'https://itunes.apple.com/us/app/guestops/id582206023';
echo geturl($longgoog);
echo geturl($longapple);

/*
if ($android || $palmpre || $berry == true) 
{ header('Location: https://play.google.com/store/apps/details?id=com.guestpass.app'); }
else if ($iphone || $ipod || $ipad == true)
{ header('Location: https://itunes.apple.com/us/app/guestops/id582206023'); }
else
{ header('Location: https://beta.grata.com/about/'); }
*/

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
    $url = preg_replace("/\/.*$/is" , "" ,$url);
    return $url;
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

    return "$domain".'/'."$out";
  }
