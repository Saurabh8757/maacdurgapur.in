<?php
$img = imagecreatetruecolor(4000, 4000);
imagefilledrectangle($img, 0, 0, 3999, 3999, imagecolorallocate($img, 255, 0, 0));
imagepng($img, 'test_5mb.png', 5);
imagedestroy($img);

$img2 = imagecreatetruecolor(5000, 5000);
imagefilledrectangle($img2, 0, 0, 4999, 4999, imagecolorallocate($img2, 0, 255, 0));
imagejpeg($img2, 'test_8mb.jpg', 100);
imagedestroy($img2);
echo "Done";
