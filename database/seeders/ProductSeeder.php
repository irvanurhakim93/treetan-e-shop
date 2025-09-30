<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Products;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $folder = 'albums';

          $productNames = [
            'amon-amarth-deceiver' => 'Amon Amarth - Deceiver',
            'batushka-litourgiya' => 'Batushka - Litourgiya',
            'darkthrone-transilvanian' => 'Darkthrone - Transilvanian Hunger',
            'bongabonga' => 'Bonga Bonga - Self Titled',
            'vlaar-blekmetal' => 'Vlaar - Blekmetal',
        ];

        $files = Storage::disk('public')->files('albums');
        $hargaList = [1000,1000,1000,1000,1000];

        foreach ($files as $f){
            $filename = pathinfo($f, PATHINFO_FILENAME);

             Products::create([
                'nama_produk' => $productNames[$filename] ?? ucfirst($filename),
                'harga' => $hargaList[array_rand($hargaList)],
                'image_path' => $f // Simpan path-nya, misalnya: albums/amon-amarth-deceiver.jpg
            ]);
        }
    }
}
