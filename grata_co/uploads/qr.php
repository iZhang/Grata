<?php

//create qr code

$path = "../QRChange/";
    
//generate qr code and save to filename fname.png
include('/Users/iZhang/Downloads/phpqrcode/phpqrcode.php');
QRcode::png("http://192.168.2.118:8888/alex_project_files/QRChange/detect.php");
//$fname = "$path".".png"; 
//QRcode::png("../detect.php", $fname); 
//$qrcode = $path.$fname;

