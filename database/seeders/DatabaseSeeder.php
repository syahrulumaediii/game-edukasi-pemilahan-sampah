<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Super Admin
        User::updateOrCreate(
            ['email' => 'admin@detektifsampah.com'],
            [
                'name' => 'Super Admin Detektif',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'nama_sekolah' => null,
            ]
        );

        // 2. Buat Demo Guru
        User::updateOrCreate(
            ['email' => 'guru@detektifsampah.com'],
            [
                'name' => 'Bu Guru Ayu',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'nama_sekolah' => 'SD Negeri Cerdas Ceria 1',
            ]
        );

        // 3. Buat Master Bank Soal Default (20 Soal)
        $defaultQuestions = [
            // --- ORGANIK (Hijau) ---
            [
                'nama_sampah' => 'Kulit Pisang',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/kulit_pisang.svg',
                'fakta_edukasi' => 'Kulit pisang mudah membusuk dan bisa diolah menjadi pupuk kompos alami!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Daun Kering',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/daun_kering.svg',
                'fakta_edukasi' => 'Daun kering bisa ditumpuk bersama tanah untuk jadi pupuk tanaman!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Sisa Sayur',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/sisa_sayur.svg',
                'fakta_edukasi' => 'Sisa sayuran mentah sangat bagus untuk campuran kompos!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Cangkang Telur',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/cangkang_telur.svg',
                'fakta_edukasi' => 'Cangkang telur mengandung kalsium yang bagus untuk menyuburkan tanah!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Sisa Nasi',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/sisa_nasi.svg',
                'fakta_edukasi' => 'Nasi sisa bisa membusuk dengan cepat dan menghasilkan nutrisi bagi tanah!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Apel Busuk',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/apel_busuk.svg',
                'fakta_edukasi' => 'Apel busuk adalah bahan organik yang disukai cacing tanah untuk kompos!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kulit Jeruk',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/kulit_jeruk.svg',
                'fakta_edukasi' => 'Kulit jeruk memberikan aroma segar pada kompos dan mengusir serangga hama.',
                'is_default' => true,
            ],

            // --- ANORGANIK (Kuning) ---
            [
                'nama_sampah' => 'Botol Plastik',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/botol_plastik.svg',
                'fakta_edukasi' => 'Botol plastik butuh waktu ratusan tahun untuk hancur. Yuk didaur ulang!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kaleng Minuman',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/kaleng_minuman.svg',
                'fakta_edukasi' => 'Kaleng dari aluminium bisa dilelehkan untuk dibuat kaleng baru!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kardus Bekas',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/kardus_bekas.svg',
                'fakta_edukasi' => 'Kardus bekas bisa dihancurkan dan dibuat jadi kertas baru!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Sedotan Plastik',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/sedotan_plastik.svg',
                'fakta_edukasi' => 'Sedotan plastik berbahaya bagi hewan laut jika dibuang sembarangan!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Gelas Kaca',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/gelas_kaca.svg',
                'fakta_edukasi' => 'Kaca bisa didaur ulang berulang kali tanpa mengurangi kualitasnya!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kantong Plastik',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/kantong_plastik.svg',
                'fakta_edukasi' => 'Kantong plastik sekali pakai mencemari lingkungan. Kurangi pemakaiannya ya!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kertas Koran',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/kertas_koran.svg',
                'fakta_edukasi' => 'Koran bekas bisa didaur ulang menjadi buku tulis baru!',
                'is_default' => true,
            ],

            // --- B3 / BERBAHAYA (Merah) ---
            [
                'nama_sampah' => 'Baterai Bekas',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/baterai_bekas.svg',
                'fakta_edukasi' => 'Baterai mengandung zat kimia beracun yang berbahaya jika dibuang di tanah!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Lampu Bohlam',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/lampu_bohlam.svg',
                'fakta_edukasi' => 'Lampu bekas mengandung gas merkuri yang berbahaya bagi pernapasan kita!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Obat Kedaluwarsa',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/obat_kedaluwarsa.svg',
                'fakta_edukasi' => 'Obat yang sudah basi harus dibuang khusus agar tidak meracuni lingkungan!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Semprotan Nyamuk',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/semprotan_nyamuk.svg',
                'fakta_edukasi' => 'Kaleng semprotan mengandung gas bertekanan tinggi yang mudah meledak!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Termometer Rusak',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/termometer_rusak.svg',
                'fakta_edukasi' => 'Cairan perak di dalam termometer lama adalah raksa yang sangat beracun!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Botol Deterjen',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/botol_deterjen.svg',
                'fakta_edukasi' => 'Sisa bahan kimia sabun cuci pekat berbahaya untuk ekosistem air!',
                'is_default' => true,
            ],
        ];

        foreach ($defaultQuestions as $question) {
            Question::updateOrCreate(
                ['nama_sampah' => $question['nama_sampah']],
                $question
            );
        }
    }
}
