<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Document</title>
</head>
<body>
	<h1>View Record </h1>
	<p><b>View All</b> | <a href="view-paginated.php">View Paginated</a></p>
	<?php
	include ('connectdb.php') ;
	if($result = $connection->query('select * from players order by id'))
		{
			if($result->num_rows>0)
			{
				echo "<table border ='1' cellpadding='10'>";
				echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th></th></tr>";
				while($row = $result->fetch_object())
				{
					echo "<tr>";
					echo "<td>" .$row->id. "</td>";
					echo "<td>" .$row->firstname. "</td>";
					echo "<td>" .$row->lastname. "</td>";
					echo "<td><a href='records.php?id="  .$row->id."'>Edit</a></td>";
				 echo "<td><a href='delete.php?id=" . $row->id . "'>Delete</a></td>";

				}
				echo "</table>";
			} else
			{
				echo "No Result to display.";
			}
		} else 
		{
			echo "Error : " .$connection->error;
		}
		$connection->close();
		?>

		<a href = "records.php">Add New Record </a>
	</body>
</html>
