<?php 
session_start();

if(!isset($_SESSION['login'])){
  header('Location: ../client/login.html');
}

			// establish a database connection to your Oracle database.
$usernamed = 'username';
$passwordd = 'password';
$servername = 'servername';
$servicename = 'servicename';
$connection = $servername."/".$servicename;
       
$conn = oci_connect($usernamed, $passwordd, $connection);
$memberuid = $_SESSION["memberuid"];


$rejectedFried = $_POST["rejectedFried"];


if(!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

if(isset($_POST["rejectedFried"])) {

	$stid = oci_parse($conn, "delete from FB_FRIENDSHIP WHERE FB_MEMBER_UID=:memberuid1_bv AND FB_MEMBER_UID1=:memberuid_bv ");
  oci_bind_by_name($stid, ":memberuid1_bv", $rejectedFried);
  oci_bind_by_name($stid, ":memberuid_bv", $memberuid);

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);  // For oci_execute errors pass the statement handle
    print htmlentities($e['message']);
    print "\n<pre>\n";
    print htmlentities($e['sqltext']);
    printf("\n%".($e['offset']+1)."s", "^");
	print  "\n</pre>\n";
	echo "Rejecting friend Request error ";
}
else { 
	echo "Friend was successfully added";
	header('Location: ./HomePage.php');
}



oci_close($conn);

}







?>







