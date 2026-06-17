Add-Type -AssemblyName System.Drawing

$img_path = "c:\xampp\htdocs\maacdurgapur\public\frontend\images\maac\icons\transparent-logo.png"
$out_path = "c:\xampp\htdocs\maacdurgapur\public\frontend\images\maac\icons\mascot-logo.png"

$img = [System.Drawing.Image]::FromFile($img_path)
$bmp = new-object System.Drawing.Bitmap($img)

# Crop the bottom 45% to remove the hands and lower body
$cropHeight = [int]($bmp.Height * 0.55)
$rect = New-Object System.Drawing.Rectangle(0, 0, $bmp.Width, $cropHeight)
$cropped = $bmp.Clone($rect, $bmp.PixelFormat)

# Find bounding box of the remaining non-transparent pixels
$minX = $cropped.Width
$minY = $cropped.Height
$maxX = 0
$maxY = 0

for ($y = 0; $y -lt $cropped.Height; $y++) {
    for ($x = 0; $x -lt $cropped.Width; $x++) {
        $pixel = $cropped.GetPixel($x, $y)
        if ($pixel.A -gt 0) {
            if ($x -lt $minX) { $minX = $x }
            if ($x -gt $maxX) { $maxX = $x }
            if ($y -lt $minY) { $minY = $y }
            if ($y -gt $maxY) { $maxY = $y }
        }
    }
}

if ($minX -le $maxX -and $minY -le $maxY) {
    $bbox = New-Object System.Drawing.Rectangle($minX, $minY, ($maxX - $minX + 1), ($maxY - $minY + 1))
    $final = $cropped.Clone($bbox, $cropped.PixelFormat)
    $final.Save($out_path, [System.Drawing.Imaging.ImageFormat]::Png)
    $final.Dispose()
} else {
    $cropped.Save($out_path, [System.Drawing.Imaging.ImageFormat]::Png)
}

$cropped.Dispose()
$bmp.Dispose()
$img.Dispose()

Write-Output "Done cropping"
