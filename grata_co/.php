<?php

            header("Cache-Control: no-cache, must-revalidate");

            header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");

            header("Status: 301 Moved Permanently");

            header("Location: http://local-dev.customshort.com/");

            ignore_user_abort(true);

            include('/Users/iZhang/Desktop/Development/grata_co/admin/index.php');
 
            clickanalytics('');