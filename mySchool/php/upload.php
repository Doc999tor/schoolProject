<?php
	if(!empty($_FILES['image'])){
		$ext = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
                $image = time().'.'.$ext;
                move_uploaded_file($_FILES["image"]["tmp_name"], './uploads/'.$image);
		// echo "Image uploaded successfully as ".$image;
            echo $image;
	}

	// private function uploadPic() {
	// $uploadfile = 'uploads/' . basename($_FILES['product_picture']['name']);
	// move_uploaded_file($_FILES['product_picture']['tmp_name'], $uploadfile);

	// return $_FILES['product_picture']['name'];
	// }
?>