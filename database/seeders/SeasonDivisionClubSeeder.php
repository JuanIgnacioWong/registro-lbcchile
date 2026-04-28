<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Division;
use App\Models\Season;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeasonDivisionClubSeeder extends Seeder
{
    public function run(): void
    {
        $season = Season::query()->updateOrCreate(
            ['year' => (int) now()->year],
            ['name' => 'Temporada '.now()->year, 'is_active' => true]
        );

        $divisionNames = ['Primera Division', 'Segunda Division', 'Sub 17'];

        foreach ($divisionNames as $divisionName) {
            $division = Division::query()->updateOrCreate(
                ['season_id' => $season->id, 'slug' => Str::slug($divisionName)],
                ['name' => $divisionName, 'is_active' => true]
            );

            foreach (['Club LBC Norte', 'Club LBC Centro', 'Club LBC Sur'] as $clubName) {
                Club::query()->updateOrCreate(
                    [
                        'season_id' => $season->id,
                        'division_id' => $division->id,
                        'slug' => Str::slug($clubName),
                    ],
                    [
                        'name' => $clubName,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
