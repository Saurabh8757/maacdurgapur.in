<?php


namespace App\Helper\admin;
use App\Models\OurCourse;


use App\Models\SiteInformationModel;

class siteInformation
{
    /*** Site Info ***/
    public static function siteInfo()
    {
        $info['site_name'] = SiteInformationModel::where('key','site_name')->value('value');
        $info['site_logo'] = SiteInformationModel::where('key','site_logo')->value('value');
        $info['fav_icon'] = SiteInformationModel::where('key','fav_icon')->value('value');
        $info['copy_right'] = SiteInformationModel::where('key','copy_right')->value('value');
        return $info;
    }
    
     public static function course($id)
    {
        $course = OurCourse::where('id', $id)->first();
        if ($course) {
            return $course->name;
        }
        return null;
    }
}
