<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$media = \App\Models\MediaAsset::latest()->first();
if ($media) {
    \App\Models\AkshaSupportingCourse::where('id', 1)->update(['featured_image_media_id' => $media->id]);
    echo "Media attached: " . $media->storage_key . "\n";
} else {
    echo "No media found.\n";
}
