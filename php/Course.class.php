<?php 

/**
* 
*/

// include 'DB.class.php';

class Course {
	public $id;
	public $name;
	public $description;
	// public $image;

	function __construct($id, $name, $description) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		// $this->image = $image;
	}

	public function insert() {
		$connection = DB::getconn();
		if ($connection->errno) {echo $connection->error;die();}

		$stmt = $connection->prepare("INSERT INTO courses (name, description) VALUES (?, ?)");
		$stmt->bind_param('ss', $this->name, $this->description);
		$stmt->execute();

		if($stmt->error){
			echo $stmt->error;
		} else {
			echo "Insert new Course: ". $this->name ." success";
		}
	}

	// public function uploadPic() {
	// 	$uploadfile = 'uploads/' . basename($_FILES['product_picture']['name']);
	// 	move_uploaded_file($_FILES['product_picture']['tmp_name'], $uploadfile);

	// 	return $_FILES['product_picture']['name'];
	// }

	public function update() {
		
	}

	public function delete($id) {
		$connection = DB::getconn();
		if ($connection->errno) {echo $connection->error;die();}

		$result = $connection->query("DELETE FROM courses WHERE id = '$id'");

		if ($result) {
			echo "delete course success";
		} else {
			echo "delete course failed";
		}
	}

	public function read() {
		$connection = DB::getconn();
			if ($connection->errno) {echo $connection->error;die();}

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
	}

	public function count() {
		$connection = DB::getconn();
		if ($connection->errno) {echo $connection->error;die();}
		
		$result = $connection->query("SELECT * FROM courses" );
	
		if ($result->num_rows > 0) {
			$count = $result->num_rows;
			echo json_encode($count);
		} else {
			echo "0";
		}	
	}
	
}