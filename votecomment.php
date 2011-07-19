<?
//error_reporting(0);

$direction = strip_tags($_REQUEST['direction']);
$commentid = strip_tags($_REQUEST['commentid']);
$db = pg_pconnect("dbname=img user=slage host=localhost password=pur6Cr3j");


$result = pg_query_params($db, "select votesup,votesdown from comments where (commentid = $1);",array($commentid));
$resultarray = pg_fetch_array($result);
$votesup= $resultarray[votesup];  
$votesdown =  $resultarray[votesdown]; 

if ($direction == 'up')
{
	$votesup = $votesup + 1;
}

if ($direction == 'down')
{
        $votesdown = $votesdown + 1;
}
$result = pg_query_params($db, "select count(*) as count from votes where commentid = $1 and ip = $2;",array($commentid,$_SERVER['REMOTE_ADDR']));
$resultarray = pg_fetch_array($result);
if ($resultarray[count] < 1)
{
	// Actually do the update
	$result = pg_query_params($db, "update comments set votesup = $1, votesdown = $2 where commentid = $3;",array($votesup,$votesdown,$commentid));
}

	// Insert our IP to prevent duplicate votes
	$result = pg_query_params($db, "insert into votes (commentid,ip) values ($1,$2);",array($commentid,$_SERVER['REMOTE_ADDR']));


//echo "Votesup :" .  $votesup;
//echo "Votesdown : " . $votesdown;
//echo "CommentID : " . $commentid;
echo "Thanks for voting.";
$resultarray = pg_fetch_array($result);
$fileid = $resultarray[fileid];  //Fileid is the id in the database for this url

?>
