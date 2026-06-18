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

Route::get('/',[PageController::class, 'index'])->name('home');
Route::get('/maac',[PageController::class, 'maac'])->name('maac');
Route::get('/aksha',[PageController::class, 'aksha'])->name('aksha');
Route::get('/space-e-fic',[PageController::class, 'space_e_fic'])->name('space_e_fic');
Route::get('/fcq',[PageController::class, 'fcq'])->name('fcq');
Route::get('/showcase',[PageController::class, 'showcase'])->name('showcase');
Route::get('/blog',[PageController::class, 'blog'])->name('blog');
Route::get('/faq',[PageController::class, 'faq'])->name('faq');
Route::get('/web-design-ui-ux-course',[PageController::class, 'web'])->name('web');

Route::get('/motion-graphics',[PageController::class, 'motion'])->name('motion');



Route::post('career-counselling',[PageController::class, 'counselling'])->name('career_counselling');

Route::get('terms-and-condition',[PageController::class, 'terms'])->name('terms_and_condition');
