<?php 

include 'DB.class.php';

$connection = DB::getconn();
	if ($connection->errno) {
		echo $connection->error;
			die();
		}

	$result = $connection->query("SELECT admins.id as id, admins.name as name, admins.phone as phone, admins.email as email, admins.image as image, roles.name as role FROM admins INNER JOIN roles on roles.id = admins.role_id");

	$row = array();

	if ($result->num_rows > 0) {
		while ($r = $result->fetch_assoc()) {
			$rows[] = $r;
		}
		echo json_encode($rows);
	} else {
		echo "0 results";
	}
