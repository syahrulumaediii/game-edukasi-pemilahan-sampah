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
                'gambar' => 'storage/images/sampah/7xG4W68dpyyeauaJsSzdxjlF5zoyheTbO0w4Zfyg.jpg',
                'fakta_edukasi' => 'Kulit pisang mudah membusuk dan bisa diolah menjadi pupuk kompos alami!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Daun Kering',
                'kategori' => 'organik',
                'gambar' => 'storage/images/sampah/xXdrxcJhaTAZTlUNxLNwRn4P5egPE6lz9jhjtQFR.jpg',
                'fakta_edukasi' => 'Daun kering bisa ditumpuk bersama tanah untuk jadi pupuk tanaman!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Sisa Sayur',
                'kategori' => 'organik',
                'gambar' => 'storage/images/sampah/j2gP4YI3Nws9J3mSJsgyF97mCSFCRySSUeszhTe7.jpg',
                'fakta_edukasi' => 'Sisa sayuran mentah sangat bagus untuk campuran kompos!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Cangkang Telur',
                'kategori' => 'organik',
                'gambar' => 'storage/images/sampah/QajCDExxJlfQDn9l4JKH240Yn3hRX1zqFH3TxKah.jpg',
                'fakta_edukasi' => 'Cangkang telur mengandung kalsium yang bagus untuk menyuburkan tanah!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Sisa Nasi',
                'kategori' => 'organik',
                'gambar' => 'images/sampah/1787589660_6a8c741c38147.png',
                'fakta_edukasi' => 'Nasi sisa bisa membusuk dengan cepat dan menghasilkan nutrisi bagi tanah!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Apel Busuk',
                'kategori' => 'organik',
                'gambar' => 'storage/images/sampah/x7kmIsvR5A1TwUl9nZPmUBRcxtLsNXY5uGTbr7eT.jpg',
                'fakta_edukasi' => 'Apel busuk adalah bahan organik yang disukai cacing tanah untuk kompos!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kulit Jeruk',
                'kategori' => 'organik',
                'gambar' => 'storage/images/sampah/sUsrZjAwJBobsVHwxaBUkeH5V6H1ic7I7sczOQA6.jpg',
                'fakta_edukasi' => 'Kulit jeruk memberikan aroma segar pada kompos dan mengusir serangga hama.',
                'is_default' => true,
            ],

            // --- ANORGANIK (Kuning) ---
            [
                'nama_sampah' => 'Botol Plastik',
                'kategori' => 'anorganik',
                'gambar' => 'storage/images/sampah/OoKBSJmJ1iTae40aKCg37anopfnLsQa0BEmjqnbL.jpg',
                'fakta_edukasi' => 'Botol plastik butuh waktu ratusan tahun untuk hancur. Yuk didaur ulang!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kaleng Minuman',
                'kategori' => 'anorganik',
                'gambar' => 'storage/images/sampah/Jv9vyfpRBTzD6Lutqld2Vjn6XmNaBSeyzcoxpO4U.jpg',
                'fakta_edukasi' => 'Kaleng dari aluminium bisa dilelehkan untuk dibuat kaleng baru!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kardus Bekas',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/1786065444_6a7532245baaf.png',
                'fakta_edukasi' => 'Kardus bekas bisa dihancurkan dan dibuat jadi kertas baru!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Sedotan Plastik',
                'kategori' => 'anorganik',
                'gambar' => 'storage/images/sampah/uX1rrP5qSAZ5nI77J35eOQFLqNTJ9ofxguoVDaia.jpg',
                'fakta_edukasi' => 'Sedotan plastik berbahaya bagi hewan laut jika dibuang sembarangan!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Gelas Kaca',
                'kategori' => 'anorganik',
                'gambar' => 'storage/images/sampah/lftc3wreUsUidFfkokN9OC7kt5gv784keqipoL2R.jpg',
                'fakta_edukasi' => 'Kaca bisa didaur ulang berulang kali tanpa mengurangi kualitasnya!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kantong Plastik',
                'kategori' => 'anorganik',
                'gambar' => 'storage/images/sampah/miY8yAuuesUHKrUiSpWxk9UKbP2yOemm3yRQhp1R.jpg',
                'fakta_edukasi' => 'Kantong plastik sekali pakai mencemari lingkungan. Kurangi pemakaiannya ya!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Kertas Koran',
                'kategori' => 'anorganik',
                'gambar' => 'images/sampah/1786065627_6a7532db311d6.png',
                'fakta_edukasi' => 'Koran bekas bisa didaur ulang menjadi buku tulis baru!',
                'is_default' => true,
            ],

            // --- B3 / BERBAHAYA (Merah) ---
            [
                'nama_sampah' => 'Baterai Bekas',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/1786065736_6a7533484dfc3.png',
                'fakta_edukasi' => 'Baterai mengandung zat kimia beracun yang berbahaya jika dibuang di tanah!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Lampu Bohlam',
                'kategori' => 'b3',
                'gambar' => 'images/sampah/1786065696_6a753320ee309.png',
                'fakta_edukasi' => 'Lampu bekas mengandung gas merkuri yang berbahaya bagi pernapasan kita!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Obat Kedaluwarsa',
                'kategori' => 'b3',
                'gambar' => 'storage/images/sampah/ELJiQsvGkPSopTlfI1iMsFU5xkCgb4yxEF8B9g6i.png',
                'fakta_edukasi' => 'Obat yang sudah basi harus dibuang khusus agar tidak meracuni lingkungan!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Semprotan Nyamuk',
                'kategori' => 'b3',
                'gambar' => 'storage/images/sampah/FT0e9gUYK10GwR3MTUi69vOaN45KRDgOLrIXZNpe.jpg',
                'fakta_edukasi' => 'Kaleng semprotan mengandung gas bertekanan tinggi yang mudah meledak!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Termometer Rusak',
                'kategori' => 'b3',
                'gambar' => 'storage/images/sampah/z3G2eiwLOP2SGh83OObZoz0LylEqSo1wsdEuIq8g.jpg',
                'fakta_edukasi' => 'Cairan perak di dalam termometer lama adalah raksa yang sangat beracun!',
                'is_default' => true,
            ],
            [
                'nama_sampah' => 'Botol Deterjen',
                'kategori' => 'b3',
                'gambar' => 'storage/images/sampah/BbdCZPMpgVOOrv3ZE8d1PwJQ1ZPX6GKeVrUO8qzT.jpg',
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
