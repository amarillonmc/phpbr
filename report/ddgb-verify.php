<?php
session_start();

header("Content-type: image/png");
$image = imagecreate(60,20);
$background_color = imagecolorallocate ($image, 219, 236, 255);
$blue = imagecolorallocate($image, 0, 90, 190);
imagestring($image,5,8,2,$_SESSION["ddgbcode"],$blue);
imagepng($image);
imagedestroy($image);
?>