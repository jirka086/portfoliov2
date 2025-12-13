# PNG Optimization Script
# Install OptiPNG: choco install optipng
# Or use online tools like TinyPNG

$images = Get-ChildItem "images\*.png"
foreach ($img in $images) {
    Write-Host "Optimizing $($img.Name)..."
    # You can use OptiPNG or convert to WebP here
}
