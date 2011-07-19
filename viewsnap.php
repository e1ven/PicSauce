<?php
//error_reporting(0);



$url = trim($_REQUEST["url"]);
if (valid_url($url) != true) { 
	$url = "http://" . $url;
	if (valid_url($url) != true) {
		exit;
		}
}
$url = SmartUrlEncode($url);
$imgurl= "http://images.websnapr.com/?size=size&key=z0ogNoQEl4ur&url=" . $url;
$contents = file_get_contents($imgurl);



//This will only look at the first 8000B of the file or so. This is better for speed, but loses accuracy.
//$contents = fread($fhandle,2541);

$hash = hash("sha256",$contents);
//echo $hash;
//echo ("<br> " . $imgurl);


$fhandle2 = fopen("/tmp/websnaps/" . $hash,w);
fwrite($fhandle2,$contents);
fclose($fhandle2);


if ($hash == "86cee16a4cf2cf68e81b9097847446c95bf5793a96f2be9c9ad2346d5b436734")
	{
	$NOTLOADED = true;
	echo("<html><head>	<meta http-equiv=\"refresh\" content=\"5\"/></head><body>");
        $RAND = rand(1,6);		
	echo ("<img src=\"images/notcached" . $RAND . ".png\" border=\"1\" width=404 height=304/>");

	}
else
	{
	$NOTLOADED = false;
	echo("<html><body>");
 echo ("<img src=\"http://images.websnapr.com/?size=size&key=z0ogNoQEl4ur&url=" . $url . "\" border=\"1\" width=404 height=304/>");

	}

function valid_url($str)
{
return ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) ? FALSE : TRUE;
}

function SmartUrlEncode($url){
// URL Encode ONLY the parts after the HTTP
$url=str_replace("://","str-colon-slash-slash-imagefun",$url);
$url=str_replace("/","string-slash-imagefun",$url);
$url=str_replace("?","question-mark-imagefun",$url);
$url=rawurlencode($url);
$url=str_replace("question-mark-imagefun","?",$url);
$url=str_replace("string-slash-imagefun","/",$url);
$url=str_replace("str-colon-slash-slash-imagefun","://",$url);
return $url;
}

?>
