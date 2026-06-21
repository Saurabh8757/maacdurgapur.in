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
// SEO 301 Redirects for MAAC Legacy URLs
Route::redirect('/maac', '/animation-vfx-gaming-institute-durgapur', 301);
Route::redirect('/maac-durgapur', '/animation-vfx-gaming-institute-durgapur', 301);
Route::redirect('/animation', '/animation-vfx-gaming-institute-durgapur', 301);
Route::redirect('/vfx', '/animation-vfx-gaming-institute-durgapur', 301);
Route::redirect('/gaming', '/animation-vfx-gaming-institute-durgapur', 301);

// SEO 301 Redirects for AKSHA Legacy URLs
Route::redirect('/aksha', '/digital-marketing-course-durgapur', 301);
Route::redirect('/digital-marketing', '/digital-marketing-course-durgapur', 301);
Route::redirect('/digital-marketing-course', '/digital-marketing-course-durgapur', 301);
Route::redirect('/marketing-course', '/digital-marketing-course-durgapur', 301);

// SEO 301 Redirects for SPACE-E-FIC Legacy URLs
Route::redirect('/space-e-fic', '/robotics-classes-for-kids-durgapur', 301);
Route::redirect('/robotics', '/robotics-classes-for-kids-durgapur', 301);
Route::redirect('/robotics-course', '/robotics-classes-for-kids-durgapur', 301);
Route::redirect('/robotics-for-kids', '/robotics-classes-for-kids-durgapur', 301);

// Official SEO Routes (Names kept identical so frontend navigation continues working seamlessly)
Route::get('/animation-vfx-gaming-institute-durgapur',[PageController::class, 'maac'])->name('maac');
Route::get('/digital-marketing-course-durgapur',[PageController::class, 'aksha'])->name('aksha');
Route::get('/robotics-classes-for-kids-durgapur',[PageController::class, 'space_e_fic'])->name('space_e_fic');
Route::get('/fcq',[PageController::class, 'fcq'])->name('fcq');
Route::get('/showcase',[PageController::class, 'showcase'])->name('showcase');
Route::get('/blog',[PageController::class, 'blog'])->name('blog');
Route::get('/faq',[PageController::class, 'faq'])->name('faq');
Route::get('/web-design-ui-ux-course',[PageController::class, 'web'])->name('web');

Route::get('/motion-graphics',[PageController::class, 'motion'])->name('motion');



Route::post('career-counselling',[PageController::class, 'counselling'])->name('career_counselling');

Route::get('terms-and-condition',[PageController::class, 'terms'])->name('terms_and_condition');
