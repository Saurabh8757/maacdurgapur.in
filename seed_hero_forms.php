<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Brand;
use App\Models\LeadFormField;

$maac = Brand::where('slug', 'maac')->first();

if ($maac) {
    // Check if hero fields already exist for MAAC
    $existing = LeadFormField::where('brand_id', $maac->id)->where('form_type', 'hero')->count();
    
    if ($existing == 0) {
        $fields = [
            [
                'field_name' => 'name',
                'label' => 'Full Name',
                'type' => 'text',
                'placeholder' => 'Full Name *',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 10,
                'options' => null
            ],
            [
                'field_name' => 'phone',
                'label' => 'Phone Number',
                'type' => 'phone',
                'placeholder' => 'Phone Number*',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 20,
                'options' => null
            ],
            [
                'field_name' => 'email',
                'label' => 'Email Address',
                'type' => 'email',
                'placeholder' => 'E-mail Address*',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 30,
                'options' => null
            ],
            [
                'field_name' => 'course_id',
                'label' => 'Course of Interest',
                'type' => 'select',
                'placeholder' => 'Select Course of Interest*',
                'is_required' => true,
                'is_active' => true,
                'sort_order' => 40,
                'options' => null
            ],
            [
                'field_name' => 'location',
                'label' => 'Preferred Location',
                'type' => 'select',
                'placeholder' => 'Prefered Location',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 50,
                'options' => json_encode(['Durgapur', 'Burdwan / Bardhaman', 'Bolpur / Santiniketan', 'Bankura', 'Asansol / Raniganj', 'Purulia', 'Panagarh', 'Suri', 'Other'])
            ]
        ];

        foreach ($fields as $fieldData) {
            $fieldData['brand_id'] = $maac->id;
            $fieldData['form_type'] = 'hero';
            LeadFormField::create($fieldData);
        }
        echo "MAAC Hero fields seeded.\n";
    } else {
        echo "MAAC Hero fields already exist.\n";
    }
}
