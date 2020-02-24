<?php 
session_start();
if(!isset($_SESSION['login'])){
  header('Location: login.html');
}
			// establish a database connection to your Oracle database.
$username = 's3667123';
$password = 'P@ssw0rd';
$servername = 'talsprddb01.int.its.rmit.edu.au';
$servicename = 'CSAMPR1.ITS.RMIT.EDU.AU';
$connection = $servername."/".$servicename;          
$conn = oci_connect($username, $password, $connection);
$memberuid = $_SESSION["memberuid"];
?>
<html>
<head>
<style>
</style>
</head>
<body>


<?php
$PostedText = $_POST["postedText"];

if(!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
else
{			
    $stid = oci_parse($conn, "INSERT INTO FB_POST (  LIKES_COUNT, BODY,FB_MEMBER_UID,DATE_CREATED)
    VALUES ( :likes_count_bv,:body_bv, :memberuid_bv , TO_DATE(:dateCreated_bv, 'DD/MM/YYYY'))");

$currentDate =  date("Y-m-d");  
$dateCreated = date("d/m/Y", strtotime($currentDate));

$likes_count = 0;


oci_bind_by_name($stid, ":likes_count_bv", $likes_count);
oci_bind_by_name($stid, ":body_bv", $PostedText);
oci_bind_by_name($stid, ":memberuid_bv", $memberuid);
oci_bind_by_name($stid, ":dateCreated_bv", $dateCreated );



$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);  // For oci_execute errors pass the statement handle
    print htmlentities($e['message']);
    print "\n<pre>\n";
    print htmlentities($e['sqltext']);
    printf("\n%".($e['offset']+1)."s", "^");
	print  "\n</pre>\n";
	echo "Accepting Request error happened";
}
else { 
  header('Location: ../server/HomePage.php');
}

oci_close($conn);

}
?>


</body>
</html>

