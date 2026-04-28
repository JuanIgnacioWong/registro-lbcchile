<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@lbcchile.com'],
            [
                'name' => 'Administrador LBC',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            PlatformSettingsSeeder::class,
            SeasonDivisionClubSeeder::class,
        ]);
    }
}
