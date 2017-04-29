<?php 
/**
* 
*/
// include 'DB.class.php';

class Student extends Person {
	
	public $id;
	public $name;
	public $phone;
	public $email;
	// public $image;

	function __construct($id, $name, $phone, $email) {
		parent::__construct($id, $name, $phone, $email);
	}

	public function insert() {
		$connection = DB::getconn();
		if ($connection->errno) {echo $connection->error;die();}

		$stmt = $connection->prepare("INSERT INTO students (name, phone, email) VALUES (?, ?, ?)");
		$stmt->bind_param('sss', $this->name, $this->phone, $this->email);
		$stmt->execute();
		
		if($stmt->error){
			echo $stmt->error;
		} else {
			echo "Insert new Student: ". $this->name ." success";
		}
	}


	public function read() {
		$connection = DB::getconn();
		if ($connection->errno) {echo $connection->error;die();}

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
	}

	public function delete($id) {
		$connection = DB::getconn();
		if ($connection->errno) {echo $connection->error;die();}

		$result = $connection->query("DELETE FROM students WHERE id = '$id'");
		
		if($result) {
			echo "delete student success";
		} else {
			echo "delete student failed";
		}
	}


	public function count() {
		$connection = DB::getconn();
		if ($connection->errno) {echo $connection->error;die();}
		
		$result = $connection->query("SELECT * FROM students");
	
		if ($result->num_rows > 0) {
			$count = $result->num_rows;
			echo json_encode($count);
		} else {
			echo "0";
		}	
	}

}