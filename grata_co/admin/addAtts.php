<?php

if (isset($_POST['done']))
{ fixAtts(); }
else
{ echo "Failed to Add"; }

/******Add Attributes to domain name******/

function fixAtts()
  {
    $items = $_POST['done'];
    $sb = "";
    foreach ($_POST as $key => $value)
    {
        $count = count($value);
        for ($i = 0; $i < $count-1; $i+=2)
            { $sb .= '&'.$value[$i].'='.$value[$i+1]; }
    }
    
    return $sb;
  }
