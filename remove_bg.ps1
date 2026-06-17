Add-Type -AssemblyName System.Drawing

$img_path = "c:\xampp\htdocs\maacdurgapur\public\frontend\images\maac\icons\index-logo.png"
$out_path = "c:\xampp\htdocs\maacdurgapur\public\frontend\images\maac\icons\transparent-logo.png"

$img = [System.Drawing.Image]::FromFile($img_path)
$bmp = new-object System.Drawing.Bitmap($img)

$minX = $bmp.Width
$minY = $bmp.Height
$maxX = 0
$maxY = 0

for ($y = 0; $y -lt $bmp.Height; $y++) {
    for ($x = 0; $x -lt $bmp.Width; $x++) {
        $pixel = $bmp.GetPixel($x, $y)
        if ($pixel.R -gt 240 -and $pixel.G -gt 240 -and $pixel.B -gt 240) {
            $bmp.SetPixel($x, $y, [System.Drawing.Color]::Transparent)
        } else {
            if ($x -lt $minX) { $minX = $x }
            if ($x -gt $maxX) { $maxX = $x }
            if ($y -lt $minY) { $minY = $y }
            if ($y -gt $maxY) { $maxY = $y }
        }
    }
}

$img.Dispose()

# Crop if valid bounds
if ($minX -le $maxX -and $minY -le $maxY) {
    $rect = New-Object System.Drawing.Rectangle($minX, $minY, ($maxX - $minX + 1), ($maxY - $minY + 1))
    $cropped = $bmp.Clone($rect, $bmp.PixelFormat)
    $cropped.Save($out_path, [System.Drawing.Imaging.ImageFormat]::Png)
    $cropped.Dispose()
} else {
    $bmp.Save($out_path, [System.Drawing.Imaging.ImageFormat]::Png)
}

$bmp.Dispose()
Write-Output "Done"
