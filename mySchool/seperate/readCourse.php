<?php 

include 'DB.class.php';

$connection = DB::getconn();
	if ($connection->errno) {
		echo $connection->error;
			die();
		}

	$result = $connection->query("SELECT * FROM courses");

	$row = array();

	if ($result->num_rows > 0) {
		while ($r = $result->fetch_assoc()) {
			$rows[] = $r;
		}
		echo json_encode($rows);
	} else {
		echo "0 results";
	}
