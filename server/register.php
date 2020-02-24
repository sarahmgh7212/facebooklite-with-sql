<?php
// Start the session
session_start();
?>
<html>
<body>

<?php
	
	//Receive input from clint side
	$email = $_POST['email'];
	$passwordd = $_POST['password'];
	//  $userEmail = $_POST['email'];
	 $fullName = strtolower($_POST['fullname']);
	 $DOB = date("d/m/Y", strtotime($_POST['dateofbirth']));
	 $captureDate = $_POST['dateofbirth'];

	 $gender = $_POST['gender'];
	 $screenName = strtolower($_POST['screenname']);
	 $status = $_POST['status'];
	 $visibility = strtolower($_POST['visibility']);
	 $location = $_POST['location'];
	 $dateCreated =  date("m/d/Y", time());



// establish a database connection to your Oracle database.
	$username = 's3667123';
	$password = 'P@ssw0rd';
	$servername = 'talsprddb01.int.its.rmit.edu.au';
	$servicename = 'CSAMPR1.ITS.RMIT.EDU.AU';
	$connection = $servername."/".$servicename;
				
	$conn = oci_connect($username, $password, $connection);

	
	//check if the input exist
	$exist = 0;
		   //read the file line by line
		   
		   if (!$conn) {
			$m = oci_error();
			echo $m['message'], "\n";
			exit; 
		   }
			$query = "SELECT *  FROM FB_MEMBER WHERE EMAIL = :email_bv"; 
			$stid = oci_parse($conn, $query);
			oci_bind_by_name($stid, ":email_bv", $email);
			oci_execute($stid);
			$row = oci_fetch_array($stid, OCI_ASSOC);
			//oci_fetch_array returns a row from the db.
			 if ($row) {
			 echo "The email entered already exists";
			 $exist = 1;
			  }			

	
	if($exist == 1){
		echo "The email already   exists! <br/><br/>Please enter another one via <a href='../client/register.html'>register.html</a>";
	}else{

		// store data in Oracle DB
		if(!$conn)
		{
		    $e = oci_error();
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		else
		{			
			$stid = oci_parse($conn, "INSERT INTO FB_MEMBER (EMAIL,  FULLNAME, SCRENNAME,
			PASSWORD,DOB, GENDER, LOCATION,STATUS,  VISIBILITY)
			VALUES (:email_bv,  :fullName_bv, :screenName_bv,:password_bv, TO_DATE(:DOB_bv, 'DD/MM/YYYY')
			, :gender_bv, :location_bv,:status_bv,  :visibility_bv) RETURNING MEMBERUID INTO :memberuid");

		oci_bind_by_name($stid, ":email_bv", $email);
		oci_bind_by_name($stid, ":screenName_bv", $screenName);
		oci_bind_by_name($stid, ":password_bv", $passwordd);
		oci_bind_by_name($stid, ":fullName_bv", $fullName);
		oci_bind_by_name($stid, ":status_bv", $status);
		oci_bind_by_name($stid, ":memberuid", $memberUid);
		oci_bind_by_name($stid, ":gender_bv", $gender);
		oci_bind_by_name($stid, ":DOB_bv", $DOB);
		oci_bind_by_name($stid, ":location_bv", $location);
		

		
		oci_bind_by_name($stid, ":visibility_bv", $visibility);

		oci_execute($stid);
		$_SESSION['login'] = "YES";
		$_SESSION["memberuid"] = $memberUid;
		$_SESSION['email'] = $email;
		$_SESSION["status"] = $status;
        $_SESSION["firstname"] = $fullName;
		$_SESSION["visibility"] = $visibility;
        $_SESSION["location"] = $location;
       
        

		if(isset($_SESSION['login'])){
			header('Location: ../server/HomePage.php');

		}
		
	}
	}
	oci_close($conn);
?>

</body>
</html>
