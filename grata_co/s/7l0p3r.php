<?php

            header("Cache-Control: no-cache, must-revalidate");

            header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");

            header("Status: 301 Moved Permanently");

            header("Location: http://www.google.com/about");

            ignore_user_abort(true);

            include('/var/www/html/grata.co/admin/index.php');
 
            clickanalytics('7l0p3r');