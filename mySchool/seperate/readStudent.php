<?php 

include 'DB.class.php';

$connection = DB::getconn();
	if ($connection->errno) {
		echo $connection->error;
			die();
		}

	$result = $connection->query("SELECT students.id as id, students.name as name, students.phone as phone, students.email as email, students.image as image, courses.name as course FROM students INNER JOIN courses on students.course_id = courses.id");

	$row = array();

	if ($result->num_rows > 0) {
		while ($r = $result->fetch_assoc()) {
			$rows[] = $r;
		}
		echo json_encode($rows);
	} else {
		echo "0 results";
	}


