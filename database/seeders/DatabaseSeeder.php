<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void{
        $file = new \SplFileObject(database_path('seeders/usuarios.csv'));
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);

        $header = null;
        $rows   = [];

        foreach ($file as $row) {
            if ($row === false || $row === [null]) {
                continue;
            }

            if ($header === null) {
                $header = $row;
                continue;
            }

            if (count($row) !== count($header)) {
                continue;
            }

            $data   = array_combine($header, $row);
            $rows[] = [
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($rows) === 500) {
                DB::table('users')->insert($rows);
            $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table('users')->insert($rows);
        }
    }
}
