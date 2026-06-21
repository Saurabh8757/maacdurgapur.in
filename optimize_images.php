<?php
$urls = [
    'https://storage.googleapis.com/mstudio-images-prod/15082103/61ed67d4-e69e-4a61-add3-89bdfe9ed4a4.jpeg',
    'https://storage.googleapis.com/mstudio-images-prod/15082103/d914e7dc-6bc8-4f81-a7eb-6cba187f551b.jpeg',
    'https://storage.googleapis.com/mstudio-images-prod/15082103/925cd7fc-d713-40e9-b5a7-96a1a72dfa61.jpeg',
    'https://storage.googleapis.com/mstudio-images-prod/15082103/5fbca8e3-5353-48b4-845a-c603b573e33e.jpeg'
];

$dir = __DIR__ . '/public/frontend/images/space_e_fic/bg';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

foreach ($urls as $index => $url) {
    $imgData = file_get_contents($url);
    if ($imgData === false) {
        echo "Failed to download $url\n";
        continue;
    }
    
    $sourceImage = imagecreatefromstring($imgData);
    if ($sourceImage === false) {
        echo "Failed to create image from $url\n";
        continue;
    }
    
    // Scale down if too large to optimize
    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);
    
    $newWidth = 1920;
    if ($width > $newWidth) {
        $newHeight = floor($height * ($newWidth / $width));
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($sourceImage);
        $sourceImage = $newImage;
    }
    
    // Save as webp for optimization
    $filename = $dir . '/bg_' . ($index + 1) . '.webp';
    imagewebp($sourceImage, $filename, 70); // 70% quality for good optimization
    imagedestroy($sourceImage);
    
    echo "Saved and optimized: $filename\n";
}
?>
