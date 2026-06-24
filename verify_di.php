<?php

use App\Services\Cms\CmsCourseService;
use App\Services\Cms\CmsFaqCategoryService;
use App\Services\Cms\CmsFaqService;
use App\Services\Cms\CmsFeatureService;
use App\Services\Cms\CmsShowcaseService;

try {
    app()->make(CmsCourseService::class);
    echo "CmsCourseService resolved successfully.\n";
    app()->make(CmsFaqCategoryService::class);
    echo "CmsFaqCategoryService resolved successfully.\n";
    app()->make(CmsFaqService::class);
    echo "CmsFaqService resolved successfully.\n";
    app()->make(CmsFeatureService::class);
    echo "CmsFeatureService resolved successfully.\n";
    app()->make(CmsShowcaseService::class);
    echo "CmsShowcaseService resolved successfully.\n";

    // Simulate saving CMS Data by calling the logger directly
    $logger = app()->make(\App\Services\Settings\SettingsAuditLogger::class);
    $logger->record('cms.test.save', 'cms.test', ['dummy' => 'data'], null, null);
    echo "Audit log entry simulated successfully.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
