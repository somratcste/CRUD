<?php

/*
		Allows the user to both create new records and edit existing records
	*/


include('connectdb.php');


// creates the new/edit record form
 	// since this form is used  multiple times in this file, I have made it a function that is easily reusable


function renderForm($first = '' ,$last = '' , $error = '' , $id = '')
{ ?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>
			<?php if($id != '') {echo "Edit Record" ;}
			else {echo "New Record" ;} ?>
		</title>
	</head>
	<body>
		<h1><?php if($id != '') {echo "Edit Record" ;}
		else {echo "New Record";} ?> </h1>

		<?php if ($error != ''){
			echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error
						. "</div>";
				} ?>

		<form action = '' method="post">
		<div>
			<?php  if ($id != '') { ?>
				<input type = 'hidden' name = "id" value=" <?php echo $id; ?>" />
				<p>ID : <?php echo $id; ?> </p>
				<?php } ?>

		<strong>First Name : *</strong>
		<input type ="text" name = "firstname" value="<?php echo $first; ?>" /><br/>

		<strong>Last Name: *</strong> <input type="text" name="lastname" value="<?php echo $last; ?>"/>
		<p>* required</p>
		<input type="submit" name="submit" value="Submit" />
	    </div>
		</form>
		
	</body>
	</html>


<?php }
       /*

           EDIT RECORD

        */
	// if the 'id' variable is set in the URL, we know that we need to edit a record

     if(isset($_GET['id']))
     {
     	if(isset($_POST['submit']))
     	{
     		if(is_numeric($_POST['id']))
     		{
     			$id = $_POST['id'];
     			$firstname  = htmlentities($_POST['firstname']  , ENT_QUOTES);
     			$lastname = htmlentities($_POST['lastname'], ENT_QUOTES);

     			if($firstname == '' || $lastname == '')
     			{
     				$error = "Error : Please fill in all required fields !";
     				renderForm($firstname,$lastname,$error,$id);
     			}
     			else
     			{
     				if($stmt = $connection->prepare("update players set firstname = ? , lastname = ?  where id = ?"))
     				{
     					$stmt->bind_param("ssi",$firstname,$lastname,$id);
     					$stmt->execute();
     					$stmt->close();
     				}
     				else
     				{
     					echo "Could not be prepared sql statement";
     				}

     				header("Location:view.php");
     			}
     		}
     		else
     		{
     			echo "Error";
     		}
     	}
     


		// if the form hasn't been submitted yet, get the info from the database and show the form

     else
     {
     	// make sure the 'id' value is valid
     	if(is_numeric($_GET['id']) && $_GET['id'] > 0)
     	{
     		$id = $_GET['id'];
     		if($stmt =  $connection->prepare("select * from players where id = ?"))
     		{
     			$stmt->bind_param("i", $id);
				$stmt->execute();
					
			    $stmt->bind_result($id, $firstname, $lastname);
				$stmt->fetch();
				renderForm($firstname,$lastname,NULL,$id);
				$stmt->close();
     		}
     		else
			{
				echo "Error: could not prepare SQL statement";
			}
			


     	}
     	// if the 'id' value is not valid, redirect the user back to the view.php page
     	else
     	{
     		header("Location:view.php");
     	}
     }
 }



  /*

           NEW RECORD

        */
	// if the 'id' variable is not set in the URL, we must be creating a new record


else
{
	// if the form's submit button is clicked, we need to process the form
	if(isset($_POST['submit']))
	{
		$firstname = htmlentities($_POST['firstname'] , ENT_QUOTES);
		$lastname = htmlentities($_POST['lastname'], ENT_QUOTES);

		if($firstname == ''  || $lastname == '')
		{ 
			$error = "Error Please fill in all required field";
			renderForm($firstname,$lastname,$error);
		} else
		{
			if($stmt = $connection->prepare("insert into players (firstname,lastname) values (?,?)"))
			{
				$stmt->bind_param("ss",$firstname,$lastname);
				$stmt->execute();
				$stmt->close();
			} else
			{
				echo "Error : could not prepare sql statement .";


			}
			header("Location:view.php");
		}

	}
	else
	{
		renderForm();
	}

}

   $connection->close();

?>