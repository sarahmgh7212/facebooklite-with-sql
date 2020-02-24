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
      $pending = "pending";
      $approved = "approved";

?>


<html>
<head>
<style>
.flex-container {
  display: flex;
}

.flex-container > div {
  margin: 10px;
  max-width: 200px;

}
</style>
<body>
<h1>Facebook-Lite</h1>

<?php
$currentDate =  date("Y-m-d");

$formattedDate = date("d/m/Y", strtotime($currentDate));

echo "<h2> Welcome to your profile " . ucwords($_SESSION["firstname"]) . "  </h2>";
?>
<a href="./profileSettings.php">Profile Settings</a>

<form action="./post.php" method="POST">
<h3>Post Something</h3>
 <textarea  placeholder="What do you think?" rows="7" cols="50" name="postedText" id="postedText">
</textarea>
<br>
<button type="submit">Post it!</button>
</form>
<div>

<?php 
    
if(!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$memberuid = $_SESSION["memberuid"];

$getPost = oci_parse($conn, 'SELECT  BODY, DATE_CREATED,SCRENNAME  
FROM FB_POST p LEFT OUTER JOIN FB_MEMBER m
ON p.FB_MEMBER_UID = m.MEMBERUID');
   oci_execute($getPost);
   // Build HTML table Header using fieldnames from Oracle Table
  //  for ($i = 1; $i <= $ncols; $i++) {
  //      $column_name  = oci_field_name($stid, $i);
  //      $column_type  = oci_field_type($stid, $i);

  //      echo "<td><B>$column_name";
  //      echo " ($column_type)</B></td>";
  //  }

   // Populate the table with data fetched from the Oracle table
   while ($row = oci_fetch_array($getPost, OCI_ASSOC+OCI_RETURN_NULLS)) {
       foreach ($row as $item) {
           echo " <div>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</div>";
       }
       echo "<hr>";
   }
?>
</div>
<!-- <a href="./searchFriends.php">Search Friend </a> -->
<div>
<h2>Friend Requests</h2>

<?php
$stid0 = oci_parse($conn, "SELECT MEMBERUID,FULLNAME 
FROM FB_MEMBER
WHERE MEMBERUID <> :memberuid_bv 
AND MEMBERUID IN (
    SELECT FB_MEMBER_UID FROM FB_FRIENDSHIP WHERE FB_MEMBER_UID1 = :memberuid_bv  AND FB_FRIENDSHIP.STATUS =:status_bv
)");

oci_bind_by_name($stid0, ":memberuid_bv", $memberuid);
oci_bind_by_name($stid0, ":status_bv", $pending);

oci_execute($stid0);

while ($row = oci_fetch_array($stid0, OCI_ASSOC+OCI_RETURN_NULLS)) {
   ?>
  <div class="flex-container">
  <div><form action='./rejectFriend.php' method='POST'>
    <input name='rejectedFried' id='rejectedFried' type='hidden' value="<?php echo $row['MEMBERUID'] ?>"/>
    <button type='submit'>Reject</button>
    </form></div>

  <h2><?php echo ucwords($row['FULLNAME']) ?></h2>
  <div> <form action='./acceptFriend.php' method='POST'>
<input name='acceptFriend' id='acceptFriend' type='hidden' value="<?php echo $row['MEMBERUID'] ?>"/>
<button type='submit'>Confirm</button>
</form></div>
 
</div>
 

<?php
  echo "<hr>";
}
?>


</div>
<div><h2>Search Friends</h2>
<form action="./HomePage.php" method="POST">
<input name="searchedFriend" id="searchedFriend" type="text"/>
<button type="submit">Search</button>
</form>
</div>

<?php 
    
if(!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
if(isset($_POST["searchedFriend"])) {

$SearchText = strtolower($_POST["searchedFriend"]);

$getUnaddedFriends = oci_parse($conn, "SELECT FULLNAME, MEMBERUID
FROM FB_MEMBER
WHERE MEMBERUID <> :memberuid_bv  
AND FULLNAME LIKE '%' || :term || '%'
AND MEMBERUID NOT IN (
    SELECT FB_MEMBER_UID1 FROM FB_FRIENDSHIP WHERE FB_MEMBER_UID = :memberuid_bv
    UNION ALL SELECT FB_MEMBER_UID FROM FB_FRIENDSHIP WHERE FB_MEMBER_UID1 = :memberuid_bv
)");

oci_bind_by_name($getUnaddedFriends, ":memberuid_bv", $memberuid);
oci_bind_by_name($getUnaddedFriends, ":term", $SearchText );
oci_execute($getUnaddedFriends);


$getFriendsThatSentRequest = oci_parse($conn, "SELECT FULLNAME, MEMBERUID
FROM FB_MEMBER
WHERE MEMBERUID <> :memberuid_bv 
AND FULLNAME LIKE '%' || :term || '%'
AND MEMBERUID IN (
    SELECT FB_MEMBER_UID FROM FB_FRIENDSHIP WHERE FB_MEMBER_UID1 = :memberuid_bv  AND FB_FRIENDSHIP.STATUS =:status_bv
)");

oci_bind_by_name($getFriendsThatSentRequest, ":memberuid_bv", $memberuid);
oci_bind_by_name($getFriendsThatSentRequest, ":term", $SearchText );
oci_bind_by_name($getFriendsThatSentRequest, ":status_bv", $pending);

oci_execute($getFriendsThatSentRequest);


$getFriendsThatReceivedRequest = oci_parse($conn, "SELECT FULLNAME, MEMBERUID
FROM FB_MEMBER
WHERE MEMBERUID <> :memberuid_bv 
AND FULLNAME LIKE '%' || :term || '%'
AND MEMBERUID IN (
    SELECT FB_MEMBER_UID1 FROM FB_FRIENDSHIP WHERE FB_MEMBER_UID = :memberuid_bv  AND FB_FRIENDSHIP.STATUS =:status_bv
)");

oci_bind_by_name($getFriendsThatReceivedRequest, ":memberuid_bv", $memberuid);
oci_bind_by_name($getFriendsThatReceivedRequest, ":term", $SearchText );
oci_bind_by_name($getFriendsThatReceivedRequest, ":status_bv", $pending);

oci_execute($getFriendsThatReceivedRequest);

$getAddedFrieds = oci_parse($conn, "SELECT FULLNAME, MEMBERUID
FROM FB_MEMBER
WHERE MEMBERUID <> :memberuid_bv 
AND FULLNAME LIKE '%' || :term || '%'
AND MEMBERUID IN (
  SELECT FB_MEMBER_UID1 FROM FB_FRIENDSHIP WHERE FB_MEMBER_UID = :memberuid_bv AND FB_FRIENDSHIP.STATUS =:status_bv
  UNION ALL SELECT FB_MEMBER_UID FROM FB_FRIENDSHIP WHERE FB_MEMBER_UID1 = :memberuid_bv  AND FB_FRIENDSHIP.STATUS =:status_bv
)");

oci_bind_by_name($getAddedFrieds, ":memberuid_bv", $memberuid);
oci_bind_by_name($getAddedFrieds, ":term", $SearchText );
oci_bind_by_name($getAddedFrieds, ":status_bv", $approved );


oci_execute($getAddedFrieds);


  
  
   // Populate the table with data fetched from the Oracle table
   while ($row = oci_fetch_array($getUnaddedFriends, OCI_ASSOC+OCI_RETURN_NULLS)) {
       ?>
        <h2><?php echo ucwords($row['FULLNAME']) ?></h2>
         <form action='./addFriend.php' method='POST'>
       <input name='addedFriend' id='addedFriend' type='hidden' value="<?php echo $row['MEMBERUID'] ?>"/>
       <button type='submit'>Add Friend</button>
       </form>
       <?php
       echo "<hr>";
   }
   while ($row = oci_fetch_array($getFriendsThatSentRequest, OCI_ASSOC+OCI_RETURN_NULLS)) {
   ?>
     <h2><?php echo ucwords($row['FULLNAME']) ?></h2>
  <form action='./acceptFriend.php' method='POST'>
<input name='acceptFriend' id='acceptFriend' type='hidden' value="<?php echo $row['MEMBERUID'] ?>"/>
<button type='submit'>Confirm</button>
</form>
    <form action='./rejectFriend.php' method='POST'>
    <input name='rejectedFried' id='rejectedFried' type='hidden' value="<?php echo $row['MEMBERUID'] ?>"/>
    <button type='submit'>Reject</button>
    </form>
    <?php
    echo "<hr>";
}
while ($row = oci_fetch_array($getFriendsThatReceivedRequest, OCI_ASSOC+OCI_RETURN_NULLS)) {
 ?>
   <h3><?php echo ucwords($row['FULLNAME']) ?></h3>
   <button disabled> Requested</button>
  <?php
  echo "<hr>";
}
while ($row = oci_fetch_array($getAddedFrieds, OCI_ASSOC+OCI_RETURN_NULLS)) {
?>
   <h3><?php echo ucwords($row['FULLNAME']) ?> is already a friend</h3>
    
  <?php
  echo "<hr>";
}
  };
?>

<form action="../server/logout.php" method="POST">
<button type="submit">Sign out</button>
</form>
</body>



</html>

<?php 



oci_close($conn);
?>