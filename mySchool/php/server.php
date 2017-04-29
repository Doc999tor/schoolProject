<?php 
session_start();
include 'Person.class.php';
include 'Admin.class.php';
include 'Course.class.php';
include 'Student.class.php';
include 'DB.class.php';


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$action = $request->action;


switch ($action) {

	case 'insert_admin':
		$ad_name = $request->ad_name;
		$ad_phone = $request->ad_phone;
		$ad_email = $request->ad_email;
		$ad_image = $request->ad_image;
		$ad_role_id = $request->ad_role_id;
		$ad_password = $request->ad_password;

		$admin = new Admin('', $ad_name, $ad_phone, $ad_email, $ad_image, $ad_role_id, $ad_password);
		// var_dump($admin);
		$admin->insert();
		

		break;
	
	case 'insert_student':
		$st_name = $request->st_name;
		$st_phone = $request->st_phone;
		$st_email = $request->st_email;
		// $st_image = $request->st_image;

		$student = new Student('', $st_name, $st_phone, $st_email);
		$student->insert();
		break;

	
	case 'insert_course':
		$cr_name = $request->cr_name;
		$cr_description = $request->cr_description;
		// $cr_image = $request->cr_image;
		
		$course = new Course('', $cr_name, $cr_description);
		$course->insert();
		break;	
	


	
	case 'read_admin':
		Admin::read();
		break;

	case 'read_student':	
		Student::read();
		break;

	case 'read_course':
		Course::read();	
		break;

	case 'delete_admin':
		$ad_id = $request->ad_id;
		Admin::delete($ad_id);
		break;
	
	case 'delete_student':
		$st_id = $request->st_id;
		Student::delete($st_id);
		break;
	
	case 'delete_course':
		$cr_id = $request->cr_id;
		Course::delete($cr_id);
		break;
	
	case 'count_admins';
		Admin::count();
		break;

	case 'count_students';
		Student::count();		
		break;

	case 'count_courses';
		Course::count();
		break;

	case 'login';
		$username = $request->username;
		$password = $request->password;
		Admin::login($username, $password);
		break;

	case 'logout';
		Admin::logout();
		break;

	default:
		# code...
		break;
}





