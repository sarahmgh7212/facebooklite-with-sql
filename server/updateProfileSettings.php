<?php 
session_start();
include('../server/rsa.php');
if(!isset($_SESSION['login'])){
  header('Location: login.html');
}

$username = 'username';
$password = 'password';
$servername = 'servername';
$servicename = 'servicename';
$connection = $servername."/".$servicename;          
$conn = oci_connect($username, $password, $connection);


 $screenName = strtolower($_POST['screenName']);
 $location = strtolower($_POST['location']);
 $status = strtolower($_POST['status']);
 $visibility = strtolower($_POST['visibility']);
?>

<html>
<head>
<style>
</style>
</head>
<body>

<?php 

if(!$conn)
		{
		    $e = oci_error();
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		else
		{			
			$stid = oci_parse($conn, "UPDATE FB_MEMBER SET screnname = :screenName, status = :status, location = :location, visibility =:visibility
            WHERE MEMBERUID = :memberuid");

            // INSERT INTO FB_MEMBER (EMAIL, PASSWORD, FULLNAME, SCRENNAME,
			// DOB, GENDER, STATUS, LOCATION, VISIBILITY)
			// VALUES (:email_bv, :password_bv, :fullName_bv, :screenName_bv, TO_DATE(:DOB_bv, 'DD/MM/YYYY')
			// , :gender_bv, :status_bv, :location_bv, :visibility_bv)");            
       
        oci_bind_by_name($stid, ":screenName", $screenName);
        oci_bind_by_name($stid, ":status", $status);
        oci_bind_by_name($stid, ":location", $location);
        oci_bind_by_name($stid, ":visibility", $visibility);
        oci_bind_by_name($stid, ":memberuid", $_SESSION['memberuid']);
		oci_execute($stid);
		
		}
        
        
echo " <div>
<h1>Profile was succesfully updated</h1>
</div>
<a href='./HomePage.php'> Back to Home Page</a><br>";

$_SESSION["firstname"] = $screenName;
$_SESSION["status"] = $status;
$_SESSION["location"] = $location;
$_SESSION["visibility"] = $visibility;

?>
</body>
</html>

<?php 
echo "Updated Profile Settings";
oci_close($conn);
?>
