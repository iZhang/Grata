<?php

            header("Cache-Control: no-cache, must-revalidate");

            header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");

            header("Status: 301 Moved Permanently");

            header("Location: http://www.grata.co/uploads/2009MTReport.pdf");

            ignore_user_abort(true);

            include('/var/www/html/grata.co/admin/index.php');
 
            clickanalytics('l9gnct');