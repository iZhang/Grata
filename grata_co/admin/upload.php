<?php

session_start();

if (!empty($_POST))
{ $_SESSION['name'] = $_FILES['file']['name']; }

upload();

header('Location: http://grata.co/admin/index',TRUE,302);

/*****Allow User to Upload File to Directory*****/

function upload()
  {
    $userFile = $_FILES["file"]["name"];
    $target_path = "/var/www/html/grata.co/uploads/";
    $target = $target_path.basename($_FILES['file']['name']);
    chmod($target, 0777); 
    move_uploaded_file($_FILES["file"]["tmp_name"], $target);
  }
