<html>
<body>

<?php
	
	//Receive input from clint side
	$input = $_POST['enter'];
	
	//check if the input exist
	$exist = 0;

           //read the file line by line
          $file = fopen("database.txt","r");
           while(!feof($file))  {
                 // get a line without the last “newline” character
                $line = trim(fgets($file));
                //compare the content of the input and the line
               if($line == $input){
			$exist = 1;
			break;
	     }			
              }
             fclose($file);	

	
	if($exist == 1){
		echo "The input is exist! <br/><br/>Please enter another one via <a href='client.html'>client.html</a>";
	}else{
		//open a file named "database.txt"
		$file = fopen("database.txt","a");
		//insert this input (plus a newline) into the database.txt
		fwrite($file,$input."\n");
		//close the "$file"
		fclose($file);
		echo "This user already exists <br/><br/>Please try go to login <a href='.html'>login.html</a>";
	}
?>

</body>
</html>
