<?php
	session_start();
	
	unset($_SESSION['login']);
	
	echo "You have logged out, you cannot access your  <a href='../server/HomePage.php'>Home page</a>";
	
	echo "<br/><br/>If you wish to login again go to  <a href='../client/login.html'>login.html</a> page";
?>
