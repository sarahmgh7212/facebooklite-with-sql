<?php 
session_start();

if(!isset($_SESSION['login'])){
  header('Location: login.html');
}

$username = 'username';
$password = 'password';
$servername = 'servername';
$servicename = 'servicename';
$connection = $servername."/".$servicename;          
$conn = oci_connect($username, $password, $connection);
$memberuid = $_SESSION["memberuid"];
$pending="pending";

$addedFriendUID = $_POST["addedFriend"];
echo $addedFriendUID;

if(isset($_POST["addedFriend"])) {

	$stid = oci_parse($conn, "INSERT INTO FB_FRIENDSHIP (STATUS, FB_MEMBER_UID, FB_MEMBER_UID1)
			VALUES (:status_bv, :memberuid_bv, :memberuid1_bv) ");
oci_bind_by_name($stid, ":status_bv", $pending);
oci_bind_by_name($stid, ":memberuid_bv", $memberuid);
oci_bind_by_name($stid, ":memberuid1_bv", $addedFriendUID);
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);  // For oci_execute errors pass the statement handle
    print htmlentities($e['message']);
    print "\n<pre>\n";
    print htmlentities($e['sqltext']);
    printf("\n%".($e['offset']+1)."s", "^");
	print  "\n</pre>\n";
	echo "Requesting error happened";
}
else { 
	echo "Friend was successfully added";
	header('Location: ./HomePage.php');
}



oci_close($conn);

}







?>


