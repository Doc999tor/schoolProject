<?php 

include 'DB.class.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$action = $request->action;
$cr_name = $request->cr_name;
$cr_description = $request->cr_description;
// $cr_image = $request->cr_image;


$connection = DB::getconn();
	if ($connection->errno) {
		echo $connection->error;
			die();
		}

	$result = $connection->query("INSERT INTO courses (name, description) VALUES ('$cr_name', '$cr_description')");

	if ($result === TRUE) {
    echo "New course created successfully";
} else {
    echo "Error: ";
}
