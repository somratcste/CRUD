<?php
include('connectdb.php');
if(isset($_GET['id']) && is_numeric($_GET['id']))
{
	$id = $_GET['id'];
	if($stmt = $connection->prepare("delete from players where id = ? limit  1"))
	{
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->close();
	}
	else
	{
		echo "Error : could not prepare Sql statement.";
	}
	$connection->close();
	header("Location:view.php");

}
else
{
	header('Location:view.php');
}
?>