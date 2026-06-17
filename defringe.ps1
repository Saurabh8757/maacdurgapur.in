Add-Type -AssemblyName System.Drawing

$img_path = "c:\xampp\htdocs\maacdurgapur\public\frontend\images\maac\icons\mascot-logo.png"

$img = [System.Drawing.Image]::FromFile($img_path)
$bmp = new-object System.Drawing.Bitmap($img)
$img.Dispose()

# Arrays to keep track of changes so we don't cascade in one pass
$pixelsToClear = New-Object System.Collections.ArrayList

for ($y = 1; $y -lt ($bmp.Height - 1); $y++) {
    for ($x = 1; $x -lt ($bmp.Width - 1); $x++) {
        $pixel = $bmp.GetPixel($x, $y)
        if ($pixel.A -gt 0) {
            $brightness = ($pixel.R + $pixel.G + $pixel.B) / 3.0
            
            # If it's very bright (white halo)
            if ($brightness -gt 210) {
                # Check neighbors
                $isEdge = $false
                if ($bmp.GetPixel($x-1, $y).A -eq 0) { $isEdge = $true }
                if ($bmp.GetPixel($x+1, $y).A -eq 0) { $isEdge = $true }
                if ($bmp.GetPixel($x, $y-1).A -eq 0) { $isEdge = $true }
                if ($bmp.GetPixel($x, $y+1).A -eq 0) { $isEdge = $true }
                
                if ($isEdge) {
                    $null = $pixelsToClear.Add((New-Object PSObject -Property @{X=$x; Y=$y}))
                }
            }
        }
    }
}

foreach ($p in $pixelsToClear) {
    $bmp.SetPixel($p.X, $p.Y, [System.Drawing.Color]::Transparent)
}

# Second pass for aggressive cleanup on the new edges
$pixelsToClear2 = New-Object System.Collections.ArrayList
for ($y = 1; $y -lt ($bmp.Height - 1); $y++) {
    for ($x = 1; $x -lt ($bmp.Width - 1); $x++) {
        $pixel = $bmp.GetPixel($x, $y)
        if ($pixel.A -gt 0) {
            $brightness = ($pixel.R + $pixel.G + $pixel.B) / 3.0
            if ($brightness -gt 180) {
                $isEdge = $false
                if ($bmp.GetPixel($x-1, $y).A -eq 0) { $isEdge = $true }
                if ($bmp.GetPixel($x+1, $y).A -eq 0) { $isEdge = $true }
                if ($bmp.GetPixel($x, $y-1).A -eq 0) { $isEdge = $true }
                if ($bmp.GetPixel($x, $y+1).A -eq 0) { $isEdge = $true }
                
                if ($isEdge) {
                    $null = $pixelsToClear2.Add((New-Object PSObject -Property @{X=$x; Y=$y}))
                }
            }
        }
    }
}

foreach ($p in $pixelsToClear2) {
    # Fade them to semi-transparent to anti-alias instead of hard cut
    $old = $bmp.GetPixel($p.X, $p.Y)
    $bmp.SetPixel($p.X, $p.Y, [System.Drawing.Color]::FromArgb(50, $old.R, $old.G, $old.B))
}

$bmp.Save($img_path, [System.Drawing.Imaging.ImageFormat]::Png)
$bmp.Dispose()

Write-Output "Verified and Defringed"
