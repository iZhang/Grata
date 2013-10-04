<?php

/***********Delete Files From Directory**********/

if (isset($_POST['expire']))
    { expire(); }
else if (isset($_POST['deleteTrack']))
    { deleteTrack(); }
else
    { die(); }

header('Location: http://grata.co/admin/index',TRUE,302);

function dbconnect()
  {
    $link = mysql_pconnect("localhost", "root", "s3ns89ui") or die(mysql_error());
    $db_selected = mysql_select_db("analytics", $link) or die(mysql_error());
  }

function expire()
  {  
    dbconnect();
    $id = $_POST['expire'];
    $sql = "select * from urlanalysis where id = '$id'";
    $result = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_array($result);
    $fPhp = $row['short_url'];
    $fPng = $row['qr_code'];
    $path = "../s/";
    unlink($path.$fPhp);
    unlink($path.$fPng);  
  }

function deleteTrack()
  {
    dbconnect();
    $id = $_POST['deleteTrack'];
    $sql = "delete from tracks where short_url = '$id'";
    mysql_query($sql) or die(mysql_error());
  }

function deleteFromDB()
  {
    dbconnect();
    $id = $_POST['expire'];
    $sql = "delete from urlanalysis where id = '$id'";
    $result = mysql_query($sql) or die(mysql_error());
  }
