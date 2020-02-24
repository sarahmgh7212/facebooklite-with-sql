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
$memberuid = $_SESSION["memberuid"];


$SearchText = $_POST["searchedFriend"];
$_SESSION['searchedInput']= $SearchText; 


?>

<html>
<head>
<style>
</style>
</head>
<body>
<div><h3>Search Friends</h3>
<form action="./searchFriendsResult.php" method="POST">
<input name="searchedFriend" id="searchedFriend"value="<?php echo $_SESSION['searchedInput']; ?>"/>
<button type="submit">Search</button>
</form>


<?php 
    
if(!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, "SELECT FULLNAME, MEMBERUID  FROM FB_MEMBER  where FULLNAME LIKE '%' || :term || '%' AND MEMBERUID != :memberuid_bv");
oci_bind_by_name($stid, ":memberuid_bv", $memberuid);
oci_bind_by_name($stid, ":term", $SearchText );
oci_execute($stid);
   // Build HTML table Header using fieldnames from Oracle Table
  //  for ($i = 1; $i <= $ncols; $i++) {
  //      $column_name  = oci_field_name($stid, $i);
  //      $column_type  = oci_field_type($stid, $i);

  //      echo "<td><B>$column_name";
  //      echo " ($column_type)</B></td>";
  //  }

   // Populate the table with data fetched from the Oracle table
   while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
       foreach ($row as $item) {
           echo " <div>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</div>";
       } ?>
         <form action='./addFriend.php' method='POST'>
       <input name='addedFriend' id='addedFriend' type='hidden' value="<?php echo $row['MEMBERUID'] ?>"/>
       <button type='submit'>Add Friend</button>
       </form>
       <?php
       echo "<hr>";
   }
?>


</body>
</html>

<?php 

oci_close($conn);
?>