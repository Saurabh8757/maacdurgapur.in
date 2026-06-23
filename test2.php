<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$request = new \Illuminate\Http\Request(["name" => "jhoonjhoona bhai", "phone" => "1234567898", "email" => "dxfjkhngz@gmail.com", "course_id" => "Java"]);
$controller = app()->make(App\Http\Controllers\Web\PageController::class);
var_dump($controller->counselling($request)->getContent());

