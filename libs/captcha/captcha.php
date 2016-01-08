<?php

session_start();

$str = substr( md5(time()), 0, 5 );

$image = imagecreatefromjpeg('captcha.jpg');
$text_color = imagecolorallocate($image, 100, 100, 100);

for($i = 0, $x = 20; $i < strlen($str); $i++){
	$size = mt_rand(20, 30);
	$angle = mt_rand(-20, 20);
	imagettftext($image, $size, $angle, $x, 30, $text_color, 'arial.ttf', $str{$i});
	$x += 25;
}

$_SESSION['captcha'] = $str;
header("Content-type: image/jpeg");
imagejpeg($image);