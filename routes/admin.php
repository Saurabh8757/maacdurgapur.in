<?php


use App\Http\Controllers\Admin\Cms\CmsController;
use App\Http\Controllers\Admin\Service\ServiceController;
use App\Http\Controllers\Admin\SiteInformation\SiteInformationController;
use App\Http\Controllers\Course\CourseController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Login\AdminLoginController;
use App\Http\Controllers\Admin\BrandContextController;
use App\Http\Controllers\Admin\Cms\Pages\CmsCoursePageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsFaqCategoryPageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsFaqPageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsFeaturePageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsShowcaseCategoryPageController;
use App\Http\Controllers\Admin\Cms\Pages\CmsShowcasePageController;
use App\Http\Controllers\Admin\Cms\CmsShowcaseMediaController;
use App\Http\Middleware\ResolveAdminBrandContext;



Route::get('admin-login',[AdminLoginController::class,'admin_login_page'])->name('admin_login');


Route::post('admin-login-check',[AdminLoginController::class,'admin_login_check'])->middleware('throttle:login')->name('admin_login_check');
Route::group(['as' => 'admin::', 'prefix' => 'v1/cpanel/admin', 'middleware' => ['web', 'AdminMiddleware', ResolveAdminBrandContext::class, 'revalidate']], function () {
    Route::post('/brand-context', [BrandContextController::class, 'switch'])
        ->name('brand_context.switch');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');

    /*** Lead Management Routes Start ***/
    Route::get('/leads', [\App\Http\Controllers\Admin\LeadManagementController::class, 'index'])->name('leads.index');
    Route::get('/leads/export/csv', [\App\Http\Controllers\Admin\LeadManagementController::class, 'exportCsv'])->name('leads.export.csv');
    Route::get('/leads/export/excel', [\App\Http\Controllers\Admin\LeadManagementController::class, 'exportExcel'])->name('leads.export.excel');
    Route::get('/leads/{id}', [\App\Http\Controllers\Admin\LeadManagementController::class, 'show'])->name('leads.show');
    Route::put('/leads/{id}/status', [\App\Http\Controllers\Admin\LeadManagementController::class, 'updateStatus'])->name('leads.update_status');
    Route::put('/leads/{id}/assign', [\App\Http\Controllers\Admin\LeadManagementController::class, 'assignUser'])->name('leads.assign');
    Route::post('/leads/{id}/notes', [\App\Http\Controllers\Admin\LeadManagementController::class, 'addNote'])->name('leads.add_note');
    Route::post('/leads/{id}/followups', [\App\Http\Controllers\Admin\FollowupController::class, 'store'])->name('leads.followups.store');
    Route::delete('/leads/{id}', [\App\Http\Controllers\Admin\LeadManagementController::class, 'destroy'])->name('leads.destroy');
    /*** Lead Management Routes End ***/

    /*** Followups Routes Start ***/
    Route::get('/followups', [\App\Http\Controllers\Admin\FollowupController::class, 'index'])->name('followups.index');
    Route::put('/followups/{id}/complete', [\App\Http\Controllers\Admin\FollowupController::class, 'complete'])->name('followups.complete');
    Route::put('/followups/{id}/cancel', [\App\Http\Controllers\Admin\FollowupController::class, 'cancel'])->name('followups.cancel');
    Route::delete('/followups/{id}', [\App\Http\Controllers\Admin\FollowupController::class, 'destroy'])->name('followups.destroy');
    /*** Followups Routes End ***/

    /*** WhatsApp Settings Routes Start ***/
    Route::get('/whatsapp/settings', [\App\Http\Controllers\Admin\WhatsApp\WhatsappAdminController::class, 'settings'])->name('whatsapp.settings');
    Route::post('/whatsapp/settings', [\App\Http\Controllers\Admin\WhatsApp\WhatsappAdminController::class, 'saveSettings'])->name('whatsapp.settings.save');
    Route::get('/whatsapp/templates', [\App\Http\Controllers\Admin\WhatsApp\WhatsappAdminController::class, 'templates'])->name('whatsapp.templates');
    Route::post('/whatsapp/templates', [\App\Http\Controllers\Admin\WhatsApp\WhatsappAdminController::class, 'saveTemplate'])->name('whatsapp.templates.save');
    /*** WhatsApp Settings Routes End ***/



    /*** Lead Forms Routes Start ***/
    Route::get('/lead-forms', [\App\Http\Controllers\Admin\LeadFormController::class, 'index'])->name('lead_forms.index');
    Route::get('/lead-forms/create', [\App\Http\Controllers\Admin\LeadFormController::class, 'create'])->name('lead_forms.create');
    Route::post('/lead-forms', [\App\Http\Controllers\Admin\LeadFormController::class, 'store'])->name('lead_forms.store');
    Route::get('/lead-forms/{id}/edit', [\App\Http\Controllers\Admin\LeadFormController::class, 'edit'])->name('lead_forms.edit');
    Route::put('/lead-forms/{id}', [\App\Http\Controllers\Admin\LeadFormController::class, 'update'])->name('lead_forms.update');
    Route::delete('/lead-forms/{id}', [\App\Http\Controllers\Admin\LeadFormController::class, 'destroy'])->name('lead_forms.destroy');
    /*** Lead Forms Routes End ***/

    /*** Blog CMS Routes Start ***/
    Route::resource('blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);
    /*** Blog CMS Routes End ***/

    /*** Profile Routes Start ***/
    Route::get('/profile/{name}', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [ProfileController::class, 'profile_update'])->name('profile_update');
    Route::post('/password-update', [ProfileController::class, 'password_update'])->name('password_update');
    Route::post('/admin-logout', [ProfileController::class, 'admin_logout'])->name('admin_logout');
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

        Route::post('aksha/major-programs/reorder', [\App\Http\Controllers\Admin\AkshaMajorProgramController::class, 'updateOrder'])
            ->name('aksha.major_programs.reorder');
        Route::patch('aksha/major-programs/{major_program}/toggle', [\App\Http\Controllers\Admin\AkshaMajorProgramController::class, 'toggleStatus'])
            ->name('aksha.major_programs.toggle');
        Route::apiResource('aksha/major-programs', \App\Http\Controllers\Admin\AkshaMajorProgramController::class)
            ->except('show')
            ->names('aksha.major_programs');

        Route::post('aksha/supporting-courses/reorder', [\App\Http\Controllers\Admin\AkshaSupportingCourseController::class, 'updateOrder'])
            ->name('aksha.supporting_courses.reorder');
        Route::patch('aksha/supporting-courses/{supporting_course}/toggle', [\App\Http\Controllers\Admin\AkshaSupportingCourseController::class, 'toggleStatus'])
            ->name('aksha.supporting_courses.toggle');
        Route::apiResource('aksha/supporting-courses', \App\Http\Controllers\Admin\AkshaSupportingCourseController::class)
            ->except('show')
            ->names('aksha.supporting_courses');
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

        Route::prefix('aksha')->name('aksha.')->group(function () {
            Route::get('major-programs', [\App\Http\Controllers\Admin\AkshaMajorProgramPageController::class, 'index'])->name('major-programs.index');
            Route::get('major-programs/create', [\App\Http\Controllers\Admin\AkshaMajorProgramPageController::class, 'create'])->name('major-programs.create');
            Route::get('major-programs/{program}/edit', [\App\Http\Controllers\Admin\AkshaMajorProgramPageController::class, 'edit'])->name('major-programs.edit');

            Route::get('supporting-courses', [\App\Http\Controllers\Admin\AkshaSupportingCoursePageController::class, 'index'])->name('supporting-courses.index');
            Route::get('supporting-courses/create', [\App\Http\Controllers\Admin\AkshaSupportingCoursePageController::class, 'create'])->name('supporting-courses.create');
            Route::get('supporting-courses/{course}/edit', [\App\Http\Controllers\Admin\AkshaSupportingCoursePageController::class, 'edit'])->name('supporting-courses.edit');
        });

        Route::resource('space-e-fic-courses', \App\Http\Controllers\Admin\SpaceEFicCourseController::class);

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

    // Placement Showcase
    Route::resource('placement-showcases', \App\Http\Controllers\Admin\PlacementShowcaseController::class)->except(['show']);
    Route::post('placement-showcases/toggle-active', [\App\Http\Controllers\Admin\PlacementShowcaseController::class, 'toggleActive'])->name('placement-showcases.toggle-active');
    Route::post('placement-showcases/toggle-featured', [\App\Http\Controllers\Admin\PlacementShowcaseController::class, 'toggleFeatured'])->name('placement-showcases.toggle-featured');
    Route::post('placement-showcases/reorder', [\App\Http\Controllers\Admin\PlacementShowcaseController::class, 'reorder'])->name('placement-showcases.reorder');

    // Recruiters
    Route::resource('recruiters', \App\Http\Controllers\Admin\RecruiterController::class)->except(['show']);
    Route::post('recruiters/toggle-active', [\App\Http\Controllers\Admin\RecruiterController::class, 'toggleActive'])->name('recruiters.toggle-active');
    Route::post('recruiters/toggle-featured', [\App\Http\Controllers\Admin\RecruiterController::class, 'toggleFeatured'])->name('recruiters.toggle-featured');
    Route::post('recruiters/reorder', [\App\Http\Controllers\Admin\RecruiterController::class, 'reorder'])->name('recruiters.reorder');



    /*** Team Member Start ***/
    Route::get('/course',[CourseController::class,'index'])->name('course');
    Route::get('/add-course',[CourseController::class,'add'])->name('add_course');
    Route::post('/save-course',[CourseController::class,'save'])->name('save_course');
    Route::get('/edit-course/{id}',[CourseController::class,'edit'])->name('edit_course');
    Route::post('/update-course/{id}',[CourseController::class,'update'])->name('update_course');
    Route::delete('/delete-course/{id}',[CourseController::class,'delete'])->name('delete_course');
    Route::post('/status-course',[CourseController::class,'status'])->name('status_course');
    /*** Team Member End ***/





    /*** services Start ***/
    Route::get('/services',[ServiceController::class,'index'])->name('services');
    Route::get('/add-services',[ServiceController::class,'add'])->name('add_services');
    Route::post('/save-services',[ServiceController::class,'save'])->name('save_services');
    Route::get('/edit-services/{id}',[ServiceController::class,'edit'])->name('edit_services');
    Route::post('/update-services/{id}',[ServiceController::class,'update'])->name('update_services');
    Route::delete('/delete-services/{id}',[ServiceController::class,'delete'])->name('delete_services');
    Route::post('/status-services',[ServiceController::class,'status'])->name('status_services');
    /*** services End ***/


});
