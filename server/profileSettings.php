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

$selectedScreenName = $_SESSION['firstname'];
$selectedLocation = $_SESSION['location'];
$selectedStatus = $_SESSION['status'];
$selectedVisibility = $_SESSION['visibility'];

?>


<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<title>Facebook-Lite</title>
<style>
</style>
</head>
<body>
  

<a href='./HomePage.php'> Back</a><br>

<div class="container">
<h3>Profile Settings:</h3>
<form action="./updateProfileSettings.php" method="POST">
<div class="form-group">
<label for="fname">Screen Name</label>
<input class="form-control" placeholder="Screen Name" name="screenName" id="screenName" value="<?php echo $_SESSION['firstname']; ?>"/>
</div>
<div class="form-group">
<label for="location">Location</label>
<input class="form-control"  placeholder="Location" name="location" id="location" value="<?php echo $_SESSION['location']; ?>" />
</div>
<div class="form-group">
<label for="status">Status</label>
<select class="form-control"   type="text" name="status" id="status" >
<option <?php if($selectedStatus == 'Single'){echo("selected");}?>>Single</option>
<option <?php if($selectedStatus == 'Married'){echo("selected");}?>>Married</option>
</select>
</div>
<div class="form-group">
<label for="visibility">Visibility</label>
<select  class="form-control"  type="text" name="visibility" id="visibility" >
<option <?php if($selectedVisibility == 'public'){echo("selected");}?>> public</option>
<option <?php if($selectedVisibility == 'friends-only'){echo("selected");}?>>friends-only</option>
<option <?php if($selectedVisibility == 'private'){echo("selected");}?>>private</option>
</select> 
</div>
<div class="form-group">
<button  type="submit" class="btn btn-primary form-control" >Submit</button>

</div>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 
</body>
</html>

<?php 
oci_close($conn);
?>
