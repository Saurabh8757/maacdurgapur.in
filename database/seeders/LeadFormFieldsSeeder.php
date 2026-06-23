<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeadFormField;
use App\Models\Brand;
use App\Models\OurCourse;

class LeadFormFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maac = Brand::where('slug', 'maac')->first();
        $aksha = Brand::where('slug', 'aksha')->first();
        $space = Brand::where('slug', 'space-e-fic')->first();

        // --- MAAC Fields ---
        if ($maac) {
            $maacFields = [
                ['field_name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'placeholder' => 'Full Name *', 'is_required' => true, 'sort_order' => 10],
                ['field_name' => 'phone', 'label' => 'Phone Number', 'type' => 'phone', 'placeholder' => 'Phone Number*', 'is_required' => true, 'sort_order' => 20],
                ['field_name' => 'email', 'label' => 'E-mail Address', 'type' => 'email', 'placeholder' => 'E-mail Address*', 'is_required' => true, 'sort_order' => 30],
                ['field_name' => 'course_id', 'label' => 'Course of Interest', 'type' => 'select', 'placeholder' => 'Select Course of Interest*', 'is_required' => true, 'sort_order' => 40, 'options' => json_encode(array_values(OurCourse::where('status', 'Active')->pluck('name', 'id')->toArray()))],
                ['field_name' => 'location', 'label' => 'Prefered Location', 'type' => 'select', 'placeholder' => 'Prefered Location', 'is_required' => false, 'sort_order' => 50, 'options' => json_encode(['Durgapur', 'Burdwan', 'Bolpur', 'Bankura', 'Asansol', 'Purulia', 'Kolkata', 'Other'])],
                ['field_name' => 'message', 'label' => 'Message', 'type' => 'textarea', 'placeholder' => 'Tell Us about Your interest / query', 'is_required' => false, 'sort_order' => 60],
            ];
            foreach ($maacFields as $field) {
                LeadFormField::updateOrCreate(
                    ['brand_id' => $maac->id, 'field_name' => $field['field_name']],
                    $field
                );
            }
        }

        // --- AKSHA Fields ---
        if ($aksha) {
            $akshaFields = [
                ['field_name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'placeholder' => 'Full Name *', 'is_required' => true, 'sort_order' => 10],
                ['field_name' => 'phone', 'label' => 'Phone Number', 'type' => 'phone', 'placeholder' => 'Phone Number *', 'is_required' => true, 'sort_order' => 20],
                ['field_name' => 'email', 'label' => 'E-mail Address', 'type' => 'email', 'placeholder' => 'E-mail Address *', 'is_required' => true, 'sort_order' => 30],
                ['field_name' => 'course_id', 'label' => 'Course', 'type' => 'select', 'placeholder' => 'Select Course *', 'is_required' => true, 'sort_order' => 40, 'options' => json_encode([
                    'Professional program in 3d animation',
                    'Professional program in 3d animation and Vfx',
                    'Diploma in core programming and languages',
                    'Professional program in digital marketing and Ai analytics'
                ])],
                ['field_name' => 'location', 'label' => 'Prefered Location', 'type' => 'select', 'placeholder' => 'Prefered Location', 'is_required' => false, 'sort_order' => 50, 'options' => json_encode(['Durgapur', 'Burdwan', 'Bolpur', 'Bankura', 'Asansol', 'Purulia', 'Kolkata', 'Other'])],
            ];
            foreach ($akshaFields as $field) {
                LeadFormField::updateOrCreate(
                    ['brand_id' => $aksha->id, 'field_name' => $field['field_name']],
                    $field
                );
            }
        }

        // --- SPACE-E-FIC Fields ---
        if ($space) {
            $spaceFields = [
                ['field_name' => 'name', 'label' => 'Parent / Child Name', 'type' => 'text', 'placeholder' => 'Parent / Child Name *', 'is_required' => true, 'sort_order' => 10],
                ['field_name' => 'phone', 'label' => 'Phone Number', 'type' => 'phone', 'placeholder' => 'Phone Number*', 'is_required' => true, 'sort_order' => 20],
                ['field_name' => 'email', 'label' => 'E-mail Address', 'type' => 'email', 'placeholder' => 'E-mail Address*', 'is_required' => true, 'sort_order' => 30],
                ['field_name' => 'course_id', 'label' => 'Class Level', 'type' => 'select', 'placeholder' => 'Select Class Level*', 'is_required' => true, 'sort_order' => 40, 'options' => json_encode([
                    'Junior Explorer (Class 3-5)',
                    'Coding Builder (Class 6-8)'
                ])],
            ];
            foreach ($spaceFields as $field) {
                LeadFormField::updateOrCreate(
                    ['brand_id' => $space->id, 'field_name' => $field['field_name']],
                    $field
                );
            }
        }
    }
}
