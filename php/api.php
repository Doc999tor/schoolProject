<?php  

include 'DB.class.php';


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$action = $request->action;


switch ($action) {

	case 'insert_admin':
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

		if ($result) {
    		echo "New Admin created successfully";
		} else {
    		echo "Error: ";
		}

		break;
	
	case 'insert_student':
		$st_name = $request->st_name;
		$st_phone = $request->st_phone;
		$st_email = $request->st_email;

		$connection = DB::getconn();
		if ($connection->errno) {
		echo $connection->error;
			die();
		}

		$result = $connection->query("INSERT INTO students (name, phone, email) VALUES ('$st_name', '$st_phone', '$st_email')");

		if ($result) {
		   	echo "New Student created successfully";
		} else {
		   	echo "Error: ";
		}

		break;

	
	case 'insert_course':
		$cr_name = $request->cr_name;
		$cr_description = $request->cr_description;
		
		$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}

		$result = $connection->query("INSERT INTO courses (name, description) VALUES ('$cr_name', '$cr_description')");

		if ($result) {
    		echo "New course created successfully";
		} else {
    		echo "Error: ";
		}

		break;	
	


	
	case 'read_admin':

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

	break;

	case 'read_student':
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

	break;

	case 'read_course':
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
	break;

	case 'delete_admin':
		$ad_id = $request->ad_id;

		$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}

		$result = $connection->query("DELETE FROM admins WHERE id = '$ad_id'");

		if($result) {
			echo "delete admin success";
		}else{
			echo "delete admin failed";
		}
	break;
	
	case 'delete_student':
		$st_id = $request->st_id;

		$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}

		$result = $connection->query("DELETE FROM students WHERE id = '$st_id'");
		
		if($result) {
			echo "delete student success";
		}else{
			echo "delete student failed";
		}
	break;
	
	case 'delete_course':
		$cr_id = $request->cr_id;

		$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}

		$result = $connection->query("DELETE FROM courses WHERE id = '$cr_id'");

		if($result) {
			echo "delete course success";
		}else{
			echo "delete course failed";
		}
	break;
	
	case 'count_admins';
		$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}
		$result = $connection->query("SELECT * FROM admins" );
	
		if ($result->num_rows > 0) {
			$count = $result->num_rows;
			echo json_encode($count);
		}else{
			echo "0";
		}	
	
	break;

	case 'count_students';
		$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}
		$result = $connection->query("SELECT * FROM students");
	
		if ($result->num_rows > 0) {
			$count = $result->num_rows;
			echo json_encode($count);
		}else{
			echo "0";
		}

				
	break;

	case 'count_courses';
	$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}
		$result = $connection->query("SELECT * FROM courses" );
	
		if ($result->num_rows > 0) {
			$count = $result->num_rows;
			echo json_encode($count);
		}else{
			echo "0";
		}
	break;

	default:
		# code...
		break;

	case 'check_admin';
	session_start();
	$connection = DB::getconn();
		if ($connection->errno) {
			echo $connection->error;
			die();
		}
		$stmt = $connection->prepare("SELECT role_id, image FROM admins WHERE name = ? AND password = ? ");
 		$stmt->bind_param('ss',$this->name, $this->password);
			
		$stmt->execute();
 		$stmt->store_result();
 		$stmt->bind_result($role_id, $image);

 		if($stmt->num_rows()){
  			$_SESSION['name'] = $username;
    		$_SESSION['image'] = $image;
    		$_SESSION['role_id'] = $role_id;
    		$session_data = array($username, $image, $role_id);

        	echo $session_data;
    	} else {
        	echo "Wrong Username or Password";
        		
    	}

}






?>