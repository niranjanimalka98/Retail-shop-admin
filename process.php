<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files'])) {
        $errors = [];
        $path = 'uploads/';
	$extensions = ['jpeg'];
		
        $all_files = count($_FILES['files']['tmp_name']);
		$fname = $_POST['fname'];

        for ($i = 0; $i < $all_files; $i++) {  
		$file_name = $fname;
		$file_tmp = $_FILES['files']['tmp_name'][$i];
		$file_type = $_FILES['files']['type'][$i];
		$file_size = $_FILES['files']['size'][$i];
		$tmp = explode('.', $fname);
		$file_ext = strtolower(end($tmp));

		$file = $path . $file_name;

		if (!in_array($file_ext, $extensions)) {
			$errors[] = '0';
		}

		if ($file_size > 100000) {
			$errors[] = '1';
		}

		if (empty($errors)) {
			move_uploaded_file($file_tmp, $file);
			$errors[] = '2';
		}
	}

	if ($errors) print_r($errors);
	
  }
}
