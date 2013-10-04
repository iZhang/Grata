<?php

include('/Users/iZhang/Desktop/Development/grata_co/php-ga-1.1.1/src/autoload.php');
use UnitedPrototype\GoogleAnalytics;

// Initialize GA Tracker
$tracker = new GoogleAnalytics\Tracker('UA-41229393-4', 'www.grata.co');

// Assemble Visitor information
$visitor = new GoogleAnalytics\Visitor();
$visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);

// Assemble Session information
$session = new GoogleAnalytics\Session();

// Assemble Page information
$page = new GoogleAnalytics\Page('/download');
$page->setTitle('Downloads');

// Track page view
$tracker->trackPageview($page, $session, $visitor);

include('/Users/iZhang/Desktop/Development/grata_co/download.php');
detectAgent();
