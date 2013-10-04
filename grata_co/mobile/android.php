<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="height=device-height,width=device-width,initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
<title></title>

<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>

<script type="text/javascript">
$(document).bind("mobileinit", function(){
$.mobile.defaultPageTransition  = 'slide';
});
</script>
<script type="text/javascript">if (navigator.userAgent.match(/iPhone/i)) {
$(window).bind('orientationchange', function(event) {
$('meta[name="viewport"]').attr('content',
'height=device-width,width=device-height,initial-scale=1.0,maximum-scale=1.0');
$('meta[name="viewport"]').attr('content',
'height=device-height,width=device-width,initial-scale=1.0,maximum-scale=1.0');
}).trigger('orientationchange');
}</script><script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>

<script language="javascript">

$(document).ready(function(){

	//handle leads

	$("#btn_lead").click(function(){

		//perform basic checks

		error='';

		if ($("#lead_name").val()=='' || $("#lead_email").val()=='' || $("#lead_phone").val()=='') 

			error="Please fill the form to continue\n";

		if (error=='' && $("#lead_email").val().indexOf('@')<1) 

			error="Please make sure your email is correct\n";

		if (error!=''){

			alert(error);

			return;

		}

		//submit

		var data_array={i: 8332, 

		name: $("#lead_name").val(), 

		email: $("#lead_email").val(), 

		phone: $("#lead_phone").val()};

				url='http://www.octomobi.com/show/lead_capture.php?callback=?';

		$.ajax({

			type: 'GET',

			url: url,

			data: data_array,

			async: false,

			jsonpCallback: 'jsonCallback',

			contentType: "application/json",

			dataType: 'jsonp',

			success: function(json) {

			   if(json.status=='success'){

					$("#lead_download").show();

					$("#lead_notification").html('Your data has been saved, thank you!').show(200);

					$("#lead_input").hide();

				}

			},

			error: function(e) {

			   console.log(e.message);

			}

		});

	});

});

</script>

</head>

<style type="text/css">

@media all and (min-width:1025px) { 

	div[data-role="page"]{width:320px !important;}

}

.bottom-icons .ui-btn .ui-btn-inner {

	padding-top: 40px !important;

}

.bottom-icons .ui-btn .ui-icon {

	width: 30px!important;

	height: 30px!important;

	margin-left: -15px !important;

	box-shadow: none!important;

	-moz-box-shadow: none!important;

	-webkit-box-shadow: none!important;

	-webkit-border-radius: none !important;

	border-radius: none !important;

}

#twitter .ui-icon {

		background:  url(http://www.octomobi.com/show/hp-twitter.png) 50% 50% no-repeat;

		background-size: 24px 22px;

}

#facebook .ui-icon {

		background:  url(http://www.octomobi.com/show/icon_facebook.png) 50% 50% no-repeat;

		background-size: 24px 22px;

}

.ui-btn-inner { padding: .6em 5px;}

.top_container {

	width:80%;

	text-align:center;

	margin:20px auto 0 auto;

	padding:20px 5px 20px 5px;

	background-color:#000;

	opacity:0.7;

	-moz-border-radius: 	.6em;

	-webkit-border-radius: 	.6em;

	border-radius: 			.6em;

}

.top_container_nb{
	
	position:fixed; 
	
	top:0px;
	
	width:100%;
	
	text-align:center;
	
}

.content_container {

	background-color:#000;

	color:#FFFFFF;

	text-shadow:none;

	padding:1px 10px 20px 10px;;

	opacity:0.7;

	-moz-border-radius: 	.6em;

	-webkit-border-radius: 	.6em;

	border-radius: 			.6em;

}

.simple_link{

		color:#000000 !important;

		text-decoration:none !important;	

	font-weight:normal !important;

}

#second-image{

	max-width:87.5%;	

}

.ui-body-c {

	background: 			#ffffff;

	background-image: -moz-linear-gradient(top, #ffffff, #ffffff);

	background-image: -webkit-gradient(linear,left top,left bottom,	color-stop(0,#ffffff),color-stop(1,#ffffff));

	text-shadow:none;	

}

</style>

<body>

<!-- HOME PAGE -->

<div data-role="page" id="home" data-theme="c"   class="home_background_image">

<div style="position:fixed;"></div>
<div class="top_container_nb"></div>
<img src="http://local-dev.mobile.com/banner.png" style="width:100%;">

<div style="text-align:center;width:100%"></div>

<div data-role="content">

      <div style="padding:10px 0 10px 0"><div style="text-align: center;"><span style="font-size: large;">Download&nbsp;Android</span></div>
<div>
<div><br />
<div></div>
<div style="text-align: center;">Connect to your personal travel assistant.</div>
<br />
<div style="text-align: center;"></div>
<div style="text-align: center;"><strong>Please select your marketplace</strong>:</div>
</div>
</div>
<div style="text-align: center;"></div>
<div style="text-align: center;"></div></div>  

<div class="ui-grid-b" style="margin-top:20px; margin-left:1px;">

<div class="ui-block-a">  

            <div style="text-align:center">

            	<a href="http://apk.gfan.com/Product/App615977.html" rel="external" target="_self" class="simple_link"><img src="http://local-dev.mobile.com/机锋市场liqu.jpg" width="48" height="48"><div style="width:90%;margin-left:5%;margin-right:5%">GFan</div></a>

            </div>

</div><div class="ui-block-b">  

            <div style="text-align:center">

            	<a href="http://www.appchina.com/app/com.guestpass.app/" rel="external" target="_self" class="simple_link"><img src="http://local-dev.mobile.com/应用汇Appchina.jpg" width="48" height="48"><div style="width:90%;margin-left:5%;margin-right:5%">AppChina</div></a>

            </div>

</div><div class="ui-block-c">  

            <div style="text-align:center">

            	<a href="http://www.wandoujia.com/apps/com.guestpass.app" rel="external" target="_self" class="simple_link"><img src="http://local-dev.mobile.com/wandoujia.jpg" width="48" height="48"><div style="width:90%;margin-left:5%;margin-right:5%">Wandoujia</div></a>

            </div>

</div><div class="ui-block-d">  

            <div style="text-align:center">

            	<a href="https://play.google.com/store/apps/details?id=com.guestpass.app" rel="external" target="_self" class="simple_link"><img src="http://local-dev.mobile.com/GooglePlay.jpg" width="48" height="48"><div style="width:90%;margin-left:5%;margin-right:5%">Google Play</div></a>

            </div>

</div></div>  



</div>

  <div data-role="footer" data-theme="c"><h3 style="padding:0;margin-left:0;margin-right:0;font-weight:normal"><a href="/show/redirect_out.php?tx_url=aHR0cDovL3d3dy5ncmF0YS5jb20=&i=8332" rel="external" target="_blank">Full Site</a> | Mobile Site</h3></div>  <div style="width:100%;text-align:center;margin-top:10px;margin-bottom:10px;font-size:11px;background-color:#FFF;padding:5px 0">

</div>

<!-- ABOUT US PAGE -->

<div data-role="page" id="about" data-theme="c" > 

<div data-role="header" data-theme="b"><a href="http://m.octomobi.com/grata" data-role="button" data-iconpos="notext" data-icon="home" data-inline="true" data-theme="b"></a><h1></h1></div>  <div data-role="content">

    <h2>Content in home page body</h2> 

  	<div style="text-align: center;"><span style="font-size: large;">Download&nbsp;Android</span></div>
<div>
<div><br />
<div></div>
<div style="text-align: center;">Connect to your personal travel assistant.</div>
<br />
<div style="text-align: center;"></div>
<div style="text-align: center;"><strong>Please select your marketplace</strong>:</div>
</div>
</div>
<div style="text-align: center;"></div>
<div style="text-align: center;"></div>     </div>

</div>

<!-- CONTENT PAGE -->

<div data-role="page" id="content" data-theme="c" > <div data-role="header" data-theme="b"><a href="http://m.octomobi.com/grata" data-role="button" data-iconpos="notext" data-icon="home" data-inline="true" data-theme="b"></a><h1></h1></div>  <div data-role="content"> 

    <h2></h2> 

  <div> </div>

    </div>

</div>

<!-- CONTACT US US PAGE -->

<div data-role="page" id="contact" data-theme="c" > 

<div data-role="header" data-theme="b"><a href="http://m.octomobi.com/grata" data-role="button" data-iconpos="notext" data-icon="home" data-inline="true" data-theme="b"></a><h1></h1></div>  <div data-role="content"> 

  	<h2></h2> 

      <div style="margin: 20px 0 5px 0">

  </div>

    </div>

</div>

<!-- DOWNLOAD PAGE -->

<div data-role="page" id="download" data-theme="c" > 

<div data-role="header" data-theme="b"><a href="http://m.octomobi.com/grata" data-role="button" data-iconpos="notext" data-icon="home" data-inline="true" data-theme="b"></a><h1></h1></div>  <div data-role="content"> 

    <h2></h2> 

    <br><br>

	  	  </div>

</div>

</body>

</html>
