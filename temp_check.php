<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

$attempt = Auth::attempt(['email' => 'admin@gmail.com', 'password' => '123456', 'user_type' => 'Admin']);
echo "Auth attempt result: " . ($attempt ? 'true' : 'false') . "\n";
