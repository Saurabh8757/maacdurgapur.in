<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Lead;
use App\Models\Brand;
use App\Models\OurCourse;

class MigrateLegacyLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:migrate-legacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates data from legacy tables (carrer_counsellings, apply_nows, contact_us) to the unified leads table.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting Legacy Lead Migration...");

        $maacBrand = Brand::where('name', 'like', '%MAAC%')->orWhere('slug', 'maac')->first();
        if (!$maacBrand) {
            $this->error("MAAC Brand not found! Migration cannot proceed safely without a default brand.");
            return 1;
        }

        $report = [
            'carrer_counsellings' => ['found' => 0, 'imported' => 0, 'duplicates' => 0, 'failed' => 0],
            'apply_nows' => ['found' => 0, 'imported' => 0, 'duplicates' => 0, 'failed' => 0],
            'contact_us' => ['found' => 0, 'imported' => 0, 'duplicates' => 0, 'failed' => 0],
        ];

        // 1. Migrate carrer_counsellings
        if (Schema::hasTable('carrer_counsellings')) {
            $counsellings = DB::table('carrer_counsellings')->get();
            $report['carrer_counsellings']['found'] = $counsellings->count();

            foreach ($counsellings as $row) {
                if (Lead::where('legacy_source', 'carrer_counsellings')->where('legacy_source_id', $row->id)->exists()) {
                    $report['carrer_counsellings']['duplicates']++;
                    continue;
                }

                try {
                    $courseName = null;
                    if ($row->course_id) {
                        $course = OurCourse::find($row->course_id);
                        if ($course) {
                            $courseName = $course->name;
                        }
                    }

                    $lead = new Lead();
                    $lead->brand_id = $maacBrand->id;
                    $lead->name = $row->name ?? null;
                    $lead->phone = $row->phone ?? null;
                    $lead->email = $row->email ?? null;
                    $lead->course_name = $courseName;
                    $lead->legacy_source = 'carrer_counsellings';
                    $lead->legacy_source_id = $row->id;
                    $lead->status = 'new';
                    // Preserve original timestamps
                    $lead->created_at = $row->created_at;
                    $lead->updated_at = $row->updated_at;
                    $lead->save();

                    $report['carrer_counsellings']['imported']++;
                } catch (\Exception $e) {
                    $report['carrer_counsellings']['failed']++;
                }
            }
        }

        // 2. Migrate apply_nows
        if (Schema::hasTable('apply_nows')) {
            $applyNows = DB::table('apply_nows')->get();
            $report['apply_nows']['found'] = $applyNows->count();

            foreach ($applyNows as $row) {
                if (Lead::where('legacy_source', 'apply_nows')->where('legacy_source_id', $row->id)->exists()) {
                    $report['apply_nows']['duplicates']++;
                    continue;
                }

                try {
                    $customData = [
                        'address' => $row->address ?? null,
                        'country' => $row->country ?? null,
                        'gender' => $row->gender ?? null,
                        'marital_status' => $row->marital_status ?? null,
                        'dob' => $row->dob ?? null,
                        'total_experience' => $row->total_experience ?? null,
                        'c_salary' => $row->c_salary ?? null,
                        'functional_area' => $row->functional_area ?? null,
                        'current_industry' => $row->current_industry ?? null,
                        'preferred_area' => $row->preferred_area ?? null,
                        'skill' => $row->skill ?? null,
                        'basic' => $row->basic ?? null,
                        'degree1' => $row->degree1 ?? null,
                        'degree2' => $row->degree2 ?? null,
                        'degree_certificate' => $row->degree_certificate ?? null,
                        'subject' => $row->subject ?? null,
                        'current_company' => $row->current_company ?? null,
                        'file' => $row->file ?? null,
                    ];

                    $lead = new Lead();
                    $lead->brand_id = $maacBrand->id;
                    $lead->name = $row->name ?? null;
                    $lead->phone = $row->mobile ?? $row->phone ?? null;
                    $lead->email = $row->email ?? null;
                    $lead->location = $row->c_location ?? null;
                    $lead->message = $row->msg ?? null;
                    $lead->custom_data = array_filter($customData); // Remove nulls
                    $lead->legacy_source = 'apply_nows';
                    $lead->legacy_source_id = $row->id;
                    $lead->status = 'new';
                    // Preserve original timestamps
                    $lead->created_at = $row->created_at;
                    $lead->updated_at = $row->updated_at;
                    $lead->save();

                    $report['apply_nows']['imported']++;
                } catch (\Exception $e) {
                    $report['apply_nows']['failed']++;
                }
            }
        }

        // 3. Migrate contact_us
        if (Schema::hasTable('contact_us')) {
            $contactUs = DB::table('contact_us')->get();
            $report['contact_us']['found'] = $contactUs->count();

            foreach ($contactUs as $row) {
                if (Lead::where('legacy_source', 'contact_us')->where('legacy_source_id', $row->id)->exists()) {
                    $report['contact_us']['duplicates']++;
                    continue;
                }

                try {
                    $customData = [
                        'subject' => $row->subject ?? null,
                        'company' => $row->company ?? null,
                    ];

                    $name = trim(($row->f_name ?? '') . ' ' . ($row->l_name ?? ''));

                    $lead = new Lead();
                    $lead->brand_id = $maacBrand->id;
                    $lead->name = $name ?: null;
                    $lead->phone = $row->phone ?? null;
                    $lead->email = $row->email ?? null;
                    $lead->message = $row->message ?? null;
                    $lead->custom_data = array_filter($customData); // Remove nulls
                    $lead->legacy_source = 'contact_us';
                    $lead->legacy_source_id = $row->id;
                    $lead->status = 'new';
                    // Preserve original timestamps
                    $lead->created_at = $row->created_at;
                    $lead->updated_at = $row->updated_at;
                    $lead->save();

                    $report['contact_us']['imported']++;
                } catch (\Exception $e) {
                    $report['contact_us']['failed']++;
                }
            }
        }

        $this->info("Migration Complete!");
        $this->table(
            ['Source Table', 'Found', 'Imported', 'Duplicates Skipped', 'Failed'],
            [
                ['Carrer Counselling', $report['carrer_counsellings']['found'], $report['carrer_counsellings']['imported'], $report['carrer_counsellings']['duplicates'], $report['carrer_counsellings']['failed']],
                ['Apply Now', $report['apply_nows']['found'], $report['apply_nows']['imported'], $report['apply_nows']['duplicates'], $report['apply_nows']['failed']],
                ['Contact Us', $report['contact_us']['found'], $report['contact_us']['imported'], $report['contact_us']['duplicates'], $report['contact_us']['failed']],
            ]
        );

        return 0;
    }
}
