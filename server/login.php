<?php 

session_start();
if(isset($_SESSION['login'])){
    header('Location: ../server/register.php');
  }
// establish a database connection to your Oracle database.
$usernamed ='username';
$passwordd = 'password';
$servername = 'servername';
$servicename = 'servicename';
$connection = $servername."/".$servicename;
            
$conn = oci_connect($usernamed, $passwordd, $connection);
?>
<html>
<body>

<?php
	
	//Receive input from clint side
    $username = $_POST['username'];
    $password = $_POST['password'];
	$identifyer=$username;
	$identifyer .=",";
	$identifyer .= $password;
    $identifyer .=",";
    $timestamp = time();

    
    
	//check if the input exist
    $exist = 0;
    $login = 0;

        
        
        if (!$conn) {
			$m = oci_error();
			echo $m['message'], "\n";
			exit; 
		   }
			$query = "SELECT MEMBERUID, SCRENNAME, PASSWORD, STATUS, LOCATION, VISIBILITY FROM FB_MEMBER WHERE EMAIL = :email_bv"; 
			 $stid = oci_parse($conn, $query);
            oci_bind_by_name($stid, ":email_bv", $username);
            oci_define_by_name($stid, 'PASSWORD', $passwordline);
            oci_define_by_name($stid, 'SCRENNAME', $screenname);
            oci_define_by_name($stid, 'STATUS', $status);
            oci_define_by_name($stid, 'LOCATION', $location);
            oci_define_by_name($stid, 'VISIBILITY', $visibility);
            oci_define_by_name($stid, 'MEMBERUID', $memberUid);

            oci_execute($stid);
            while (oci_fetch($stid))
            {
                // echo "Current Retrieved Password is  $passwordline<br>\n";
             if ($password== $passwordline){
                 $exist = 1;
             }
             break;
          }	

	if($exist == 1){

        $_SESSION['login'] = "YES";
        $_SESSION['email'] = $username;
        $_SESSION["firstname"] = $screenname;
        $_SESSION["status"] = $status;
        $_SESSION["location"] = $location;
        $_SESSION["visibility"] = $visibility;
        $_SESSION["memberuid"] = $memberUid;

        


        header('Location: ./HomePage.php');
        oci_close($conn);
        exit;
	}else{
        echo "<br></br>Wrong password or username
        <a href='../client/login.html'> Try again </a>";
        oci_close($conn);
    }
?>
</body>
</html>
