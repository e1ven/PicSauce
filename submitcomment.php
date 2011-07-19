<?
//error_reporting(0);
$hash = strip_tags($_REQUEST['hash']);
$comment = strip_tags($_REQUEST['comment']);
$db = pg_pconnect("dbname=img user=slage host=localhost password=pur6Cr3j");
$result = pg_query_params($db, "select fileid from img where (hashtype = 'sha256' and hashvalue = $1);",array($hash));
$resultarray = pg_fetch_array($result);
$fileid = $resultarray[fileid];  //Fileid is the id in the database for this url
$result = pg_query_params($db, "insert into comments (comment,fileid) values ($1,$2);",array($comment,$fileid));
$resultarray = pg_fetch_array($result);
$fileid = $resultarray[fileid];  //Fileid is the id in the database for this url



echo "Thank you for your insight!<br><a href = \"index.php\">Enter Another</a>";
?>
