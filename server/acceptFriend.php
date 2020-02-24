<?php 
session_start();

if(!isset($_SESSION['login'])){
  header('Location: ../client/login.html');
}

			// establish a database connection to your Oracle database.
$usernamed = 's3667123';
$passwordd = 'P@ssw0rd';
$servername = 'talsprddb01.int.its.rmit.edu.au';
$servicename = 'CSAMPR1.ITS.RMIT.EDU.AU';
$connection = $servername."/".$servicename;
       
$conn = oci_connect($usernamed, $passwordd, $connection);
$memberuid = $_SESSION["memberuid"];
$approved="approved";


$acceptedFriendUid = $_POST["acceptFriend"];


if(!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

if(isset($_POST["acceptFriend"])) {

	$stid = oci_parse($conn, "update FB_FRIENDSHIP SET STATUS=:status_bv WHERE FB_MEMBER_UID=:memberuid1_bv AND FB_MEMBER_UID1=:memberuid_bv ");
oci_bind_by_name($stid, ":status_bv", $approved);
oci_bind_by_name($stid, ":memberuid_bv", $memberuid);
oci_bind_by_name($stid, ":memberuid1_bv", $acceptedFriendUid);
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);  // For oci_execute errors pass the statement to get it handled
    print htmlentities($e['message']);
    print "\n<pre>\n";
    print htmlentities($e['sqltext']);
    printf("\n%".($e['offset']+1)."s", "^");
	print  "\n</pre>\n";
	echo "Accepting Request error happened";
}
else { 
	echo "Friend was successfully added";
	header('Location: ./HomePage.php');
}



oci_close($conn);

}







?>





?>


