<?php

session_start();

if (!empty($_POST))
{ $_SESSION['name'] = $_FILES['file']['name']; }

upload();

header('Location: http://local-dev.customshort.com/customize',TRUE,302);

/*****Allow User to Upload File to Directory*****/

function upload()
  {
    $userFile = $_FILES["file"]["name"];
    $target_path = "/Users/iZhang/Desktop/Development/grata_co/uploads/";
    $target = $target_path.basename($_FILES['file']['name']);
    chmod($target, 0775);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target);
  }
