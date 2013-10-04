<?php

	    header("Cache-Control: no-cache, must-revalidate");

	    header("Expires: Thu, 1 Jan 1970 00:00:00 GMT");

	    header("Status: 301 Moved Permanently");

	    header("Location: http://https://itunes.apple.com/us/app/guestops/id582206023");

	    ignore_user_abort(true);

	    include('/var/www/html/grata.co/admin/index.php');
 
	    clickanalytics();