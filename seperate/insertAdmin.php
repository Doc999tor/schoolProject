<?php 

include 'DB.class.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$action = $request->action;
$ad_name = $request->ad_name;
$ad_phone = $request->ad_phone;
$ad_email = $request->ad_email;
// $ad_image = $request->ad_image;
$ad_role_id = $request->ad_role_id;
$ad_password = $request->ad_password;


$connection = DB::getconn();
	if ($connection->errno) {
		echo $connection->error;
			die();
		}

	$result = $connection->query("INSERT INTO admins (name, phone, email, role_id, password) VALUES ('$ad_name', '$ad_phone', '$ad_email', $ad_role_id, '$ad_password')");

	if ($result === TRUE) {
    echo "New Admin created successfully";
} else {
    echo "Error: ";
}
