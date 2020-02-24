<?php 
session_start();
include('../server/rsa.php');
if(!isset($_SESSION['login'])){
  header('Location: login.html');
}

$username = 's3667123';
$password = 'P@ssw0rd';
$servername = 'talsprddb01.int.its.rmit.edu.au';
$servicename = 'CSAMPR1.ITS.RMIT.EDU.AU';
$connection = $servername."/".$servicename;          
$conn = oci_connect($username, $password, $connection);

// $SearchText = $_POST["searchedFriend"];
// echo "Search Result " . $SearchText;



?>


<html>
<head>
<style>
</style>
</head>
<body>
<div><h3>Search Friends</h3>
<form action="./searchFriendsResult.php" method="POST">
<input name="searchedFriend" id="searchedFriend" type="text"/>
<button type="submit">Search</button>
</form>
</body>
</html>

<?php 

oci_close($conn);
?>