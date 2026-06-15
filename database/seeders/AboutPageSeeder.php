<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
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
                  'key' => 'about',
                  'title' => 'ABOUT US',
                  'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                  Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer
                                  took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,
                                  but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s
                                  with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                                  publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                  'image' => 'image.jpg'
              ],
            [
                'key' => 'process',
                'title' => 'MANUFACTURING PROCESS',
                'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into
                 electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                 sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker
                 including versions of Lorem Ipsum.",
                'image' => 'image.jpg'
            ],
        ];

        foreach ($allData as $data){
            $save = new AboutPage();
            $save->key = $data['key'];
            $save->title = $data['title'];
            $save->description = $data['description'];
            $save->image = $data['image'];
            $save->save();
        }
    }
}
