<?php

if($_SERVER['REQUEST_METHOD'] == "POST") {

	// image name
	$name = sha1(uniqid(mt_rand(), true));

	// location to save cropped image
	$url = 'temp/'.$name.'.jpg';

	$dst_x = 0;
	$dst_y = 0;

	$src_x = $_POST['x']; // crop Start x
	$src_y = $_POST['y']; // crop Srart y

	$src_w = $_POST['w']; // $src_x + $dst_w
	$src_h = $_POST['h']; // $src_y + $dst_h

	// set a specific size for the image
	// the default is to grab the width and height from the cropped imagee.
	$dst_w = 240;
	$dst_h = 240;

	// remove the base64 part
	$base64 = $_POST['image'];


	// if URL is a base64 string
	if (substr($base64, 0, 5) == 'data:') {

		// remove data from image
		$base64 = preg_replace('#^data:image/[^;]+;base64,#', '', $base64);

		$base64 = base64_decode($base64);

		// create image from string
		$source = imagecreatefromstring($base64);

	}
	else {

		// strip parameters from URL
		$base64 = strtok($base64, '?');

		list($height, $width, $type) = getimagesize($base64);

		// create image
		if ($type == 1)
			$source = imagecreatefromgif($base64);
		else if ($type == 2)
			$source = imagecreatefromjpeg($base64);
		else if ($type == 3) {
			$source = imagecreatefrompng($base64);

			// keep transparent background
			imagealphablending($image, FALSE);
			imagesavealpha($image, TRUE);

		}
		else die();

	}

	// resize image variable
	$image = imagecreatetruecolor($dst_w, $dst_h);

	// process cropping and resizing of image
	imagecopyresampled($image, $source, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

	// save image
	imagejpeg($image, $url, 100);


	// return URL
	$validation = array (
		'url'     => $url
	);
	echo json_encode($validation);
}
