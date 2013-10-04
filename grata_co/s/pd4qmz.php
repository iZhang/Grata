<?php

            header("Cache-Control: no-cache, must-revalidate");

            header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");

            header("Status: 301 Moved Permanently");

            header("Location: http://www.grata.co/uploads/104 _ 12 Providence Bank a-1.pdf");

            ignore_user_abort(true);

            include('/var/www/html/grata.co/admin/index.php');
 
            clickanalytics('pd4qmz');