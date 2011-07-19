<?php
//error_reporting(0);


$starttime = microtime();
$startarray = explode(" ", $starttime);
$starttime = $startarray[1] + $startarray[0];


$url = trim($_REQUEST["url"]);
if (valid_url($url) != true) { 
	$url = "http://" . $url;
	if (valid_url($url) != true) {
		exit;
		}
}
$url = SmartUrlEncode($url);
$fhandle = fopen($url, "r");
//This will only look at the first 8000B of the file or so. This is better for speed, but loses accuracy.
$contents = fread($fhandle,2541);


//Check to see if they submitted a URL, instead of an image.


fclose($fhandle);
$hash = hash("sha256",$contents);
$db = pg_pconnect("dbname=img user=slage host=localhost password=pur6Cr3j");

if (strpos(strtolower($contents), 'html') !== false)
        {
        $MEDIATYPE = "WEBSITE";
	//If we're a website, take out the repetitive parts
        $newurl = strtolower($url); 
	$newurl = preg_replace("/www./", "", $newurl); 
	$newurl = preg_replace("/http:\/\//", "", $newurl);
	$newurl = preg_replace("/https:\/\//", "", $newurl);	
	$newurl = preg_replace("/ftp:\/\//","",$newurl);
	$newurl = preg_replace("/\//","",$newurl);
	$hash = hash("sha256",$newurl);
        }
else
        {
        $MEDIATYPE = "IMAGE";
        }



echo "
<html><head>
<link REL=\"SHORTCUT ICON\" HREF=\"favicon.ico\">

<script type=\"text/javascript\">
function addComment()
{
	var xmlHttp;
	try
	{
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		try
		{
			xmlHttp=new ActiveXObject(\"Msxml2.XMLHTTP\");
		}
		catch (e)
		{
			try
			{
				xmlHttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
			}
			catch (e)
			{
				return false;
			}
		}
	}
	xmlHttp.onreadystatechange=function()
	{
      		if(xmlHttp.readyState==4)
		{
			document.getElementById(\"LeaveComment\").innerHTML=xmlHttp.responseText;
		}
      	}	
	var url=\"submitcomment.php?comment=\";
	url += document.commentform.comment.value;
	url += \"&hash=\";
	url += document.commentform.hash.value;
	xmlHttp.open(\"GET\",url,true);
	xmlHttp.send(null);
}



function voteComment(direction,commentid)
{
	var xmlHttp;
	try
	{
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		try
		{
			xmlHttp=new ActiveXObject(\"Msxml2.XMLHTTP\");
		}
		catch (e)
		{
			try
			{
				xmlHttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
			}
			catch (e)
			{
				return false;
			}
		}
	}
	xmlHttp.onreadystatechange=function()
	{
      		if(xmlHttp.readyState==4)
		{
			document.getElementById(\"voteup\" + commentid ).style.visibility=\"hidden\";
			document.getElementById(\"voteup\" + commentid ).innerHTML=\"\";
			document.getElementById(\"votedown\" + commentid ).style.visibility=\"hidden\";
			document.getElementById(\"votedown\" + commentid ).innerHTML=\"\";
		}		
      	}
	var url=\"votecomment.php?commentid=\";
	url += commentid;
	url += \"&direction=\";
	url += direction;
	xmlHttp.open(\"GET\",url,true);
	xmlHttp.send(null);
}
function StopLoading()
{
if (!document.all)
{
window.stop();
}
else
{
window.document.execCommand('Stop');
}
}

</script>
<style>

#bar { width: 100px; height: 100px; z-index: 100;
height: expression(document.body.clientHeight + 'px' );
width: expression(document.body.clientWidth + 'px' );
z-index:1; 
display:block;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;  
background: url('bgcolor.png');
 }

* html #bar { /*\*/position: absolute; top: expression(((ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)) + 'px'); right: expression(((ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft)) + 'px');/**/ }

#foo > #bar { position: fixed;} 
</style>
</head>
<body>


<div class=\"Logo\" style=\"position:absolute;z-index:200;left:55px; top: 40px;width:88px;height: 91px;\" onclick=\"location.href='http://www.picsauce.com';\" style=\"cursor:pointer;\"></div>


<div style=\"z-index:10; display:block;position:absolute;    top:0;    left:0;    width:100%;    height:100%;        
height: expression(document.body.clientHeight + 'px');
width: expression(document.body.clientWidth + 'px');
background: url('logo.png');
background-repeat: no-repeat;\">
</div>

<div id='foo'><div id='bar'>
</div>
</div>

<div class=\"search\" style=\"position:absolute;z-index:100;left:100px; top:200px;\">


";



$count = 0;
$result = pg_query_params($db, "select fileid,count from img where (hashtype = 'sha256' and hashvalue = $1);",array($hash));
$resultarray = pg_fetch_array($result);
$fileid = $resultarray[fileid];  //Fileid is the id in the database for this url
$count = $resultarray[count];

if ($fileid == "" ) {
// Never Seen it
        $result = pg_query_params($db, "insert into img (hashtype,hashvalue,srcurl) values ('sha256',$1,$2);",array($hash,$url));
 	echo ("Status: Brand Spanken New.  ");	
$fhandle2 = fopen("/tmp/" . $hash,w);
fwrite($fhandle2,$contents);
fclose($fhandle2);


}
else
{
	echo ("Status: Previously entered. ");
}
	// We've seen this picture Before

if ($MEDIATYPE == "IMAGE")
{
	echo (" ::: We've seen this picture before " . $count . " times.  <br> ");
	echo ("<img src=\"" . $url . "\"><br>");
}
else if ($MEDIATYPE == "WEBSITE") 
{
        echo (" That website has been entered " . $count . " times.  <p><br> ");
	echo ("<div style=\"z-index: 10000; position:absolute; width: 409; height: 309; background-color:#003366; layer-background-color:#003366;  filter:alpha(opacity=50); -moz-opacity:0.5;opacity: 0.5;\"></div>"); 
	echo ("<iframe id=\"remotesite\" src=\"viewsnap.php?url=" . $url . "\" width=404 height=304 security=\"restricted\" marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=1 scrolling=no></iframe>");
	//echo ("<img src=\"http://images.websnapr.com/?size=size&key=z0ogNoQEl4ur&url=" . $url . "\" border=\"1\" width=404 height=304/>");

}

	$result = pg_query_params($db, "select comment,date,commentid from comments where (fileid = $1) order by (votesdown - votesup) ;",array($fileid));
	$resultarray = pg_fetch_array($result);
	
	if ($resultarray == "")
	{
	echo "<br>There are no comments posted for this image file yet. Post the first!";
	}
	else
	{
	for ($i=0;$i<pg_num_rows($result);$i += 1)
		{
			$commentid= pg_fetch_result($result,$i,commentid);
			echo "<hr>";
			echo "<table width = 80%><tr><td width = 80%><strong>Comment left at " .  pg_fetch_result($result,$i,date) . ".</strong></td><td id=\"voteup" . $commentid . "\"><a href=\"javascript:voteComment('up','" .  $commentid . "')\">Vote Up.</a></td><td id=\"votedown" . $commentid ."\" ><a href=\"javascript:voteComment('down','" . $commentid . "')\">Vote Down.</a></td></tr>";
			echo "<tr><td><pre>" . wordwrap(pg_fetch_result($result,$i,comment),90,"<br />\n") . "</pre></td></tr></table>";
		}
	}
	echo "<hr>";
	echo "<div id=\"LeaveComment\">Add your own comments below: ";
	echo "<form name=\"commentform\">";
	echo "<textarea name=\"comment\" cols=50 rows=10 style=\"background-color: #ABABAB\"></textarea><br>";
	echo "<input type=\"button\" value=\"Leave Comment\" name=\"Submit\" onClick=\"javascript:addComment();\">";
	echo "<input type=\"hidden\" name=\"hash\" value=\"" . $hash. "\" >";
	echo "</form>";
	echo "</div>";


$result = pg_query_params($db, "insert into seen(fileid,url,ip,useragent) values ($1,$2,$3,$4);",array($fileid,$url,$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT']));
$result = pg_query_params($db, "update img set count = $1 where fileid = $2;",array(($count + 1),$fileid));
#$server = pg_fetch_array($gameserver);
#$serverid = $server[0];

$endtime = microtime();
$endarray = explode(" ", $endtime);
$endtime = $endarray[1] + $endarray[0];
$totaltime = $endtime - $starttime;
$totaltime = round($totaltime,5);
echo "This page loaded in $totaltime seconds.";

function valid_url($str)
{
return ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) ? FALSE : TRUE;
}

function reply($str)
{
echo "<html><body>" . $str . "</body></html>";
}

function display_comments($comment)
{
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
