<?php

use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
   Artisan::call('cache:clear');
   Artisan::call('route:clear');

   return "Cache cleared successfully";
});

Route::get('/config-cache', function() {
     $exitCode = Artisan::call('config:cache');
     return 'Config cache cleared';
 });
 
 Route::get('/clear-cache', function() {
     $exitCode = Artisan::call('cache:clear');
     return 'Application cache cleared';
 });
Route::get('/',[PageController::class, 'index'])->name('home');
Route::get('/vfx-course',[PageController::class, 'vfx'])->name('vfx');

Route::get('/3d-animation-course',[PageController::class, 'animation'])->name('animation');

Route::get('/web-design-ui-ux-course',[PageController::class, 'web'])->name('web');

Route::get('/motion-graphics',[PageController::class, 'motion'])->name('motion');

Route::get('/multimedia-and-digital-design',[PageController::class, 'multimedia'])->name('multimedia');


Route::post('career-counselling',[PageController::class, 'counselling'])->name('career_counselling');

Route::get('terms-and-condition',[PageController::class, 'terms'])->name('terms_and_condition');

