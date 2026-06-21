<?php


use App\Http\Controllers\Admin\Career\CareerController;
use App\Http\Controllers\Admin\Cms\CmsController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Service\ServiceController;
use App\Http\Controllers\Admin\SiteInformation\SiteInformationController;
use App\Http\Controllers\Admin\Subscribers\SubscriberController;
use App\Http\Controllers\Admin\Team\TeamMamebrController;
use App\Http\Controllers\Course\CourseController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Login\AdminLoginController;
use App\Http\Controllers\Admin\BrandContextController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Settings\SettingsDraftController;
use App\Http\Controllers\Admin\Settings\SettingsVersionController;
use App\Http\Controllers\Admin\Cms\Pages\CmsCoursePageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsFaqCategoryPageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsFaqPageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsFeaturePageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsShowcaseCategoryPageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsShowcasePageController;
use App\Http\Controllers\Admin\Cms\CmsShowcaseMediaController;
use App\Http\Middleware\ResolveAdminBrandContext;



// Route::get('auth',[AdminLoginController::class,'admin_login'])->name('admin_login');
Route::get('admin-login',[AdminLoginController::class,'admin_login_page'])->name('admin_login');


Route::post('admin-login-check',[AdminLoginController::class,'admin_login_check'])->name('admin_login_check');
Route::group(['as' => 'admin::', 'prefix' => 'v1/cpanel/admin', 'middleware' => ['web', 'AdminMiddleware', ResolveAdminBrandContext::class, 'revalidate']], function () {
    Route::post('/brand-context', [BrandContextController::class, 'switch'])
        ->name('brand_context.switch');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings/brand', [SettingsController::class, 'brand'])
        ->name('settings.brand.index');
    Route::put('/settings/brand/draft', [SettingsDraftController::class, 'update'])
        ->name('settings.brand.draft.update');
    Route::delete('/settings/brand/draft/{definition}/override', [SettingsDraftController::class, 'resetOverride'])
        ->name('settings.brand.draft.reset');
    Route::get('/settings/brand/{definition}/versions', [SettingsVersionController::class, 'index'])
        ->name('settings.brand.versions.index');
    Route::get('/settings/brand/{definition}/versions/{version}', [SettingsVersionController::class, 'show'])
        ->name('settings.brand.versions.show');
    Route::get('/settings/global', [SettingsController::class, 'global'])
        ->name('settings.global.index');
    Route::get('/settings/global/{definition}/versions', [SettingsVersionController::class, 'index'])
        ->name('settings.global.versions.index');
    Route::get('/settings/global/{definition}/versions/{version}', [SettingsVersionController::class, 'show'])
        ->name('settings.global.versions.show');
    /*** Profile Routes Start ***/
    Route::get('/profile/{name}', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [ProfileController::class, 'profile_update'])->name('profile_update');
    Route::post('/password-update', [ProfileController::class, 'password_update'])->name('password_update');
    Route::get('/admin-logout', [ProfileController::class, 'admin_logout'])->name('admin_logout');
    /*** Profile Routes End ***/

     /*** Site Information Start ***/
     Route::get('/information',[SiteInformationController::class,'information'])->name('information');
     Route::get('/information-add',[SiteInformationController::class,'information_add'])->name('information_add');
     Route::post('/information-save',[SiteInformationController::class,'information_save'])->name('information_save');
     Route::get('/information-edit/{id}',[SiteInformationController::class,'information_edit'])->name('information_edit');
     /*** Site Information End ***/

    /*** CMS Start ***/
    Route::get('/heading',[CmsController::class,'index'])->name('cms');
    Route::get('/heading-edit/{key}',[CmsController::class,'edit'])->name('edit_cms');
    Route::post('/heading-save',[CmsController::class,'save'])->name('save_cms');
    Route::post('/heading-status',[CmsController::class,'status'])->name('status_cms');
    /*** CMS End ***/

    /*** CMS FAQ Start ***/
    Route::group(['prefix' => 'cms'], function () {
        Route::apiResource('faq-categories', \App\Http\Controllers\Admin\Cms\CmsFaqCategoryController::class)
            ->except('show')
            ->names('admin::cms.faq-categories');
        Route::apiResource('faqs', \App\Http\Controllers\Admin\Cms\CmsFaqController::class)
            ->except('show')
            ->names('admin::cms.faqs');
        Route::apiResource('courses', \App\Http\Controllers\Admin\Cms\CmsCourseController::class)
            ->except('show')
            ->names('admin::cms.courses');
        Route::apiResource('features', \App\Http\Controllers\Admin\Cms\CmsFeatureController::class)
            ->except('show')
            ->names('admin::cms.features');

        Route::apiResource('showcase-categories', \App\Http\Controllers\Admin\Cms\CmsShowcaseCategoryController::class)
            ->except('show')
            ->names('admin::cms.showcase_categories');

        Route::apiResource('showcase-projects', \App\Http\Controllers\Admin\Cms\CmsShowcaseProjectController::class)
            ->except('show')
            ->names('admin::cms.showcase_projects');

        Route::post('showcase-media', [CmsShowcaseMediaController::class, 'store'])
            ->name('cms.showcase_media.store');

        Route::patch('showcase-projects/{showcase_project}/publish', [\App\Http\Controllers\Admin\Cms\CmsShowcaseProjectController::class, 'publish'])
            ->name('admin::cms.showcase_projects.publish');

    });
    /*** CMS FAQ End ***/

    /*** CMS Admin UI Start ***/
    Route::prefix('content-management')->name('content.')->group(function () {
        Route::get('faq-categories', [CmsFaqCategoryPageController::class, 'index'])->name('faq-categories.index');
        Route::get('faq-categories/create', [CmsFaqCategoryPageController::class, 'create'])->name('faq-categories.create');
        Route::get('faq-categories/{faqCategory}/edit', [CmsFaqCategoryPageController::class, 'edit'])->name('faq-categories.edit');

        Route::get('faqs', [CmsFaqPageController::class, 'index'])->name('faqs.index');
        Route::get('faqs/create', [CmsFaqPageController::class, 'create'])->name('faqs.create');
        Route::get('faqs/{faq}/edit', [CmsFaqPageController::class, 'edit'])->name('faqs.edit');

        Route::get('courses', [CmsCoursePageController::class, 'index'])->name('courses.index');
        Route::get('courses/create', [CmsCoursePageController::class, 'create'])->name('courses.create');
        Route::get('courses/{course}/edit', [CmsCoursePageController::class, 'edit'])->name('courses.edit');

        Route::get('features', [CmsFeaturePageController::class, 'index'])->name('features.index');
        Route::get('features/create', [CmsFeaturePageController::class, 'create'])->name('features.create');
        Route::get('features/{feature}/edit', [CmsFeaturePageController::class, 'edit'])->name('features.edit');

        Route::get('showcase-categories', [CmsShowcaseCategoryPageController::class, 'index'])->name('showcase-categories.index');
        Route::get('showcase-categories/create', [CmsShowcaseCategoryPageController::class, 'create'])->name('showcase-categories.create');
        Route::post('showcase-categories', [CmsShowcaseCategoryPageController::class, 'store'])->name('showcase-categories.store');
        Route::get('showcase-categories/{category}/edit', [CmsShowcaseCategoryPageController::class, 'edit'])->name('showcase-categories.edit');
        Route::put('showcase-categories/{category}', [CmsShowcaseCategoryPageController::class, 'update'])->name('showcase-categories.update');
        Route::delete('showcase-categories/{category}', [CmsShowcaseCategoryPageController::class, 'destroy'])->name('showcase-categories.destroy');

        Route::get('showcase', [CmsShowcasePageController::class, 'index'])->name('showcase.index');
        Route::get('showcase/create', [CmsShowcasePageController::class, 'create'])->name('showcase.create');
        Route::get('showcase/{showcase}/edit', [CmsShowcasePageController::class, 'edit'])->name('showcase.edit');

    });
    /*** CMS Admin UI End ***/



    /*** Team Member Start ***/
    Route::get('/course',[CourseController::class,'index'])->name('course');
    Route::get('/add-course',[CourseController::class,'add'])->name('add_course');
    Route::post('/save-course',[CourseController::class,'save'])->name('save_course');
    Route::get('/edit-course/{id}',[CourseController::class,'edit'])->name('edit_course');
    Route::post('/update-course/{id}',[CourseController::class,'update'])->name('update_course');
    Route::get('/delete-course/{id}',[CourseController::class,'delete'])->name('delete_course');
    Route::post('/status-course',[CourseController::class,'status'])->name('status_course');
    /*** Team Member End ***/





    /*** services Start ***/
    Route::get('/services',[ServiceController::class,'index'])->name('services');
    Route::get('/add-services',[ServiceController::class,'add'])->name('add_services');
    Route::post('/save-services',[ServiceController::class,'save'])->name('save_services');
    Route::get('/edit-services/{id}',[ServiceController::class,'edit'])->name('edit_services');
    Route::post('/update-services/{id}',[ServiceController::class,'update'])->name('update_services');
    Route::get('/delete-services/{id}',[ServiceController::class,'delete'])->name('delete_services');
    Route::post('/status-services',[ServiceController::class,'status'])->name('status_services');
    /*** services End ***/

Route::get('users-details', [CourseController::class, 'usersDetails'])->name('user_detail');

});
