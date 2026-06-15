<?php

namespace Database\Seeders;

use App\Models\ContactInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allData = [
            [
                'address' => '58 Howard Street #2 San Francisco, CA 941',
                'email' => 'contact@galvanize.com',
                'phone_code' => '+68',
                'phone' => '122109876',
                'website' => 'www.galvanize.com',
                'status' => 'Active',
            ],
            [
                'address' => '58 test Street #2 San Francisco, test address',
                'email' => 'abcd@test.com',
                'phone_code' => '+68',
                'phone' => '123456789',
                'website' => 'www.test.domain',
                'status' => 'Inactive',
            ],
        ];

        foreach ($allData as $data){
            $item = new ContactInfo();
            $item->email = $data['email'];
            $item->phone_code = $data['phone_code'];
            $item->phone = $data['phone'];
            $item->website = $data['website'];
            $item->address = $data['address'];
            $item->status = $data['status'];
            $item->save();
        }
    }
}
