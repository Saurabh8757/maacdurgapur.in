<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class AdminLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = env('SEED_ADMIN_EMAIL', 'admin@maacdurgapur.com');
        $password = env('SEED_ADMIN_PASSWORD', \Illuminate\Support\Str::random(16));

        $user = new User();
        $user->name = 'Administrator';
        $user->slug_name = 'administrator';
        $user->profile_picture = 'user_image.jpg';
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->user_type = 'Admin';
        $user->save();

        // Log the generated password so it can be noted during installation
        if (!env('SEED_ADMIN_PASSWORD')) {
            $this->command->info('========================================');
            $this->command->info('ADMIN CREDENTIALS (save these now!):');
            $this->command->info('Email: ' . $email);
            $this->command->info('Password: ' . $password);
            $this->command->info('========================================');
        }
    }
}
