<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Brand;
use App\Models\LeadFormField;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $brands = Brand::all();
        
        $defaultFields = [
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

        foreach ($brands as $brand) {
            $existing = LeadFormField::where('brand_id', $brand->id)->where('form_type', 'hero')->count();
            if ($existing === 0) {
                foreach ($defaultFields as $field) {
                    $field['brand_id'] = $brand->id;
                    $field['form_type'] = 'hero';
                    LeadFormField::create($field);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Don't delete on rollback as users might have customized them
    }
};
