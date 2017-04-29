<?php 

include 'DB.class.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$action = $request->action;
$st_name = $request->st_name;
$st_phone = $request->st_phone;
$st_email = $request->st_email;
// $ad_image = $request->ad_image;


$connection = DB::getconn();
	if ($connection->errno) {
		echo $connection->error;
			die();
		}

	$result = $connection->query("INSERT INTO students (name, phone, email) VALUES ('$st_name', '$st_phone', '$st_email')");

	if ($result === TRUE) {
    echo "New Student created successfully";
} else {
    echo "Error: ";
}
