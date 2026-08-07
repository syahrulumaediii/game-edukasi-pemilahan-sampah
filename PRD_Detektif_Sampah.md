# 📄 Product Requirement Document (PRD)

**Nama Produk:** Web Game "Detektif Sampah"  
**Deskripsi:** Platform game edukasi pemilahan sampah 2D interaktif berbasis web yang dirancang khusus untuk siswa SD (Mobile-First/Tablet), dilengkapi fitur sistem kelas mandiri bagi Guru berbasis QR Code/Link.  
**Target Pengguna Utama:** Siswa SD (Kelas 1–6) & Guru SD  
**Teknologi Utama:** Laravel + Alpine.js + Tailwind CSS  

---

## 1. Visi Produk & Prinsip Desain Khusus Anak SD

### 1.1 Visi Utama
Menghadirkan media pembelajaran interaktif yang mengubah topik pemilahan sampah yang kaku menjadi game aksi refleks 2D yang menyenangkan, cepat, dan kompetitif, tanpa membebani siswa dengan proses registrasi akun yang rumit.

### 1.2 Prinsip UX/UI Khusus Siswa SD (Kelas 1–3)
* **Zero-Barrier Access:** Siswa tidak perlu membuat akun, mengingat *username*, *email*, atau *password*. Cukup Scan QR Code / Klik Link → Masukkan Nama & Pilih Kelas → Main.
* **Child-Friendly Visuals:** Menggunakan skema warna cerah (*vibrant/playful*), ilustrasi kartun lucu (bukan foto riil yang kaku), ikon berukuran besar (*fat-finger friendly*), font membulat (*Fredoka/Poppins*), dan indikator visual.
* **Instant Gratification:** Umpan balik langsung berupa efek suara (*SFX*) ceria, getaran (vibrate), animasi teks melayang (*floating text*), serta efek *combo/streak* api untuk memotivasi anak.
* **Low Literacy Tolerance:** Nama objek sampah berupa kata tunggal sederhana (misal: "Apel", "Botol", "Baterai") yang tebal dan besar di bawah ilustrasi sampah. Tidak menggunakan kalimat soal/cerita panjang.

---

## 2. Arsitektur Pengguna & Peran Akses (Role Management)

Aplikasi membagi akses menjadi **3 Tingkatan Akses**:

```text
 ┌────────────────────────────────────────────────────────────────────────┐
 │                           SISTEM WEB GAME                              │
 └───────────────────────────────────┬────────────────────────────────────┘
                                     │
       ┌─────────────────────────────┼─────────────────────────────┐
       ▼                             ▼                             ▼
[ 👑 SUPER ADMIN ]             [ 👨‍🏫 GURU ]                  [ 🧒 SISWA SD ]
- Akses Full Control         - Akses Dashboard Guru        - Tanpa Login / Guest Mode
- Kelola Akun Guru           - Buat Game / Sesi Kelas      - Scan QR Code / Link Game
- Master Bank Soal Default   - Input Soal Sampah           - Input Nama & Main
- Export Rekap Laporan       - Bagikan QR Code             - Lihat Skor & Leaderboard
                             - Rekap Nilai Kelas
```

---

## 3. Spesifikasi Fitur Detail (Functional Requirements)

### 3.1 Modul Siswa (Player Experience)

#### **FR-S01: Sesi Akses Instan (QR Code / Deep Link)**
* Siswa membuka aplikasi via URL khusus (`/play/{game_code}`) yang ter-encode di dalam QR Code.
* Sistem mengenali `game_code` dan otomatis memuat bank soal yang disiapkan oleh guru untuk kelas tersebut.

#### **FR-S02: Form Identitas Sederhana**
* Halaman selamat datang menampilkan maskot animasi "Detektif Sampah".
* Field Input:
  * **Nama Lengkap / Panggilan** (Wajib, Text Input berukuran besar).
  * **Pilihan Kelas** (Wajib, 3 Tombol Kartu Besar: "Kelas 1", "Kelas 2", "Kelas 3" - tidak menggunakan dropdown untuk mempermudah anak kecil).
* Tombol **"MULAI MAIN!"** berukuran sangat besar dengan efek 3D timbul dan animasi detak (*pulse*).

#### **FR-S03: Arena Permainan 2D (Arcade Tap Mechanics & Level Adaptif)**
* **Mekanik Adaptif Berdasarkan Kelas:**
  * **Kelas 1 (Easy):** 
    * **2 Tombol Eksekusi (Bak Sampah):** 🟢 Hijau (Organik) & 🟡 Kuning (Anorganik).
    * **Timer Global:** Hitung mundur 60 detik.
    * **Pool Sampah:** Hanya menampilkan objek sampah dasar yang sangat mudah dikenali anak (misal: Kulit Pisang, Botol Plastik).
  * **Kelas 2 & 3 (Medium):**
    * **3 Tombol Eksekusi (Bak Sampah):** 🟢 Hijau (Organik), 🟡 Kuning (Anorganik), & 🔴 Merah (B3).
    * **Timer Global:** Hitung mundur 45 detik.
    * **Pool Sampah:** Menampilkan variasi sampah yang lebih luas termasuk limbah berbahaya dasar (baterai, lampu).
* **Display Sampah (Center Canvas):** Objek sampah muncul di tengah "Kaca Pembesar Detektif". Gambar berupa ilustrasi kartun lucu beresolusi tinggi dengan label nama singkat yang tebal di bawahnya.
* **Sistem Poin & Combo Multiplier:**
  * Jawaban Benar: +100 Poin.
  * Jawaban Salah: -20 Poin (Skor tidak bisa minus di bawah 0).
  * **Combo Streak:** Menjawab benar 3x berturut-turut mengaktifkan mode *Streak 🔥* (+20 bonus poin per sampah selanjutnya).

#### **FR-S04: Audio Visual & Preloader System**
* **Asset Preloader:** Layar loading singkat sebelum game dimulai untuk mengunduh semua gambar sampah dan SFX ke memori browser agar game berjalan tanpa lag.
* **Audio SFX:**
  * Suara *Ting/Chime* gembira saat memilah dengan benar.
  * Suara *Buzz/Tet* lembut saat salah (tidak membuat anak berkecil hati).
  * Suara *Ticking Clock* saat waktu menyisakan 5 detik terakhir.
* **Visual FX:** Animasi partikel/bintang dan angka +100 melayang naik saat tombol di-tap.

#### **FR-S05: Halaman Hasil & Edukasi (Post-Game)**
* **Kalkulasi Performa:** Menampilkan Skor Akhir, Akurasi (misal: 15/18 Sampah Benar), dan Gelar Cilik (contoh: *🥇 Detektif Master Lingkungan*).
* **Review Kesalahan (Self-Correction):** Bagian khusus yang menampilkan daftar gambar sampah yang salah dipilah beserta bak sampah yang benar dan penjelasan singkat mengapa itu salah (misal: *"Karton Susu masuk Bak Kuning (Anorganik) karena bisa didaur ulang!"*).
* **Kartu Fakta Edukasi Singkat:** Menampilkan 1 pesan edukasi super pendek (maksimal 1 kalimat komunikatif) berdasarkan jenis sampah yang dimainkan.
* **Leaderboard Sesi Kelas:** Menampilkan daftar Top 10 Siswa dengan skor tertinggi di sesi game/kelas tersebut.
* **Tombol Aksi:** "Main Lagi" (Mencoba ulang sesi) atau "Keluar".

---

### 3.2 Modul Guru (Role: `guru`)

#### **FR-G01: Autentikasi Guru**
* Halaman Login khusus Guru menggunakan `email` dan `password`.
* Fitur Lupa Password sederhana.

#### **FR-G02: Manajemen Sesi Game / Kelas**
* Guru dapat membuat sesi permainan baru dengan input:
  * **Judul Game/Kelas** (misal: *"Kuis Pemilahan Sampah Kelas 4A"*).
* Sistem meng-generate **Kode Game Unik** (misal: `DETEKTIF-4A`).
* Fitur Buka/Tutup Sesi (Sesi yang ditutup tidak bisa dimainkan lagi oleh siswa).

#### **FR-G03: Input Soal/Sampah Kustom**
* Guru dapat menambah daftar sampah khusus untuk game tersebut:
  * **Nama Sampah** (string).
  * **Kategori Sampah** (Radio Button: Organik / Anorganik / B3).
  * **Upload Gambar** (JPEG/PNG, auto-crop/resize ke skala 1:1).
  * **Fakta Edukasi Singkat** (Text).
* Option: **"Gunakan Template Default Admin"** — Guru bisa langsung mengimpor 10-15 bank soal standar buatan Super Admin tanpa perlu upload manual.

#### **FR-G04: QR Code & Link Exporter**
* Tampilan khusus di Dashboard Guru yang menampilkan:
  * **QR Code Besar:** Siap ditampilkan di layar proyektor kelas atau dicetak di kertas.
  * **Direct URL Link:** Tombol "Salin Link" untuk dibagikan via WhatsApp Group Orang Tua / Guru.
  * Tombol **"Cetak Stiker/Banner QR"** (Auto-generate layout PDF siap print).

#### **FR-G05: Rekapitulasi & Analitik Kelas**
* Tabel monitoring siswa yang sedang/sudah bermain secara *real-time*.
* Kolom data: Ranking, Nama Siswa, Kelas, Total Skor, Jawaban Benar, dan Waktu Main.
* Fitur **Export Data:** Mengunduh rekap nilai seluruh siswa kelas dalam format file `.xlsx` (Excel) atau PDF.

#### **FR-G06: Mode Proyektor (Live Leaderboard View)**
* Guru dapat membuka halaman khusus `/play/{game_code}/live` yang ramah tampilan landscape/proyektor.
* Halaman ini memuat podium dinamis Top 3 dan daftar 10 siswa teratas yang ter-update secara real-time (menggunakan polling/Laravel Reverb) seiring siswa menyelesaikan permainannya.

#### **FR-G07: Analisis Miskonsepsi Siswa**
* Grafik/tabel di Dashboard Guru yang mendeteksi jenis sampah mana yang paling sering salah dipilah oleh murid di kelas tersebut (misal: *"65% Siswa salah memilah Kulit Telur ke Anorganik"*), diambil dari data counters pivot tabel `game_session_questions`.

---

### 3.3 Modul Super Admin (Role: `super_admin`)

#### **FR-A01: Manajemen Akun Guru**
* Super Admin dapat membuatkan akun login baru untuk guru-guru SD sasaran.
* Edit profil guru dan reset password.

#### **FR-A02: Master Bank Soal Default**
* Mengelola bank soal sampah standar (preset) yang dapat di-copy oleh semua guru.

#### **FR-A03: Global Dashboard & Rekap KKM**
* Menampilkan metrik utama: Total SD Sasaran, Total Guru Aktif, Total Sesi Game, dan Total Siswa SD yang Telah Memainkan Game.
* Fitur **Export Master Report:** Mengunduh seluruh rekapitulasi partisipasi se-desa untuk kebutuhan Lampiran Laporan LPPM/KKM.

---

## 4. Arsitektur Spesifikasi Teknologi (Tech Stack)

Aplikasi dibangun menggunakan pendekatan **Monolith Modern (TALL Lite)** yang sangat efisien, enteng, dan mudah dipasang di hosting standar.

```text
 ┌────────────────────────────────────────────────────────────────────────┐
 │                           CLIENT / BROWSER                             │
 │   ┌──────────────────────────┐      ┌──────────────────────────────┐   │
 │   │ Siswa (Mobile/Tablet)    │      │ Guru & Admin (Desktop/Tablet)│   │
 │   │ - Alpine.js Game Engine  │      │ - Tailwind CSS UI            │   │
 │   │ - HTML5 Canvas & Audio   │      │ - Blade Templates            │   │
 │   └─────────────┬────────────┘      └──────────────┬───────────────┘   │
 └─────────────────┼──────────────────────────────────┼───────────────────┘
                   │                                  │
                   │ (Submit Score via Fetch API)     │ (Standard HTTP/Form)
                   ▼                                  ▼
 ┌────────────────────────────────────────────────────────────────────────┐
 │                    BACKEND CORE: LARAVEL FRAMEWORK                     │
 │  ┌─────────────────────────┐      ┌─────────────────────────────────┐  │
 │  │ Routing & Middleware    │      │ Controllers & Logic             │  │
 │  │ - Role: Admin vs Guru   │      │ - GameSessionController         │  │
 │  │ - Guest Play Route      │      │ - ScoreController               │  │
 │  └─────────────────────────┘      └─────────────────────────────────┘  │
 │  ┌──────────────────────────────────────────────────────────────────┐  │
 │  │ Integrated Packages                                              │  │
 │  │ - Simple QrCode (QR Code Generator Engine)                       │  │
 │  │ - Maatwebsite Excel (Excel Export Engine)                        │  │
 │  └──────────────────────────────────────────────────────────────────┘  │
 └────────────────────────────────────┬───────────────────────────────────┘
                                      │
                                      ▼
 ┌────────────────────────────────────────────────────────────────────────┐
 │                     DATABASE ENGINE: MYSQL                             │
 └────────────────────────────────────────────────────────────────────────┘
```

### 4.1 Spesifikasi Komponen
* **Backend Framework:** Laravel 13.x (PHP 8.2+)
* **Frontend Interactive Engine:** Alpine.js 3.x (Untuk mengelola state timer, array sampah, skor, dan efek visual tanpa butuh re-load halaman).
* **CSS Framework:** Tailwind CSS 4.x (Dengan custom palet warna emerald & pastel).
* **Database Engine:** MySQL 8.0 (Atau SQLite).
* **Library Tambahan Frontend:** `Howler.js` atau Native HTML5 Audio API (untuk sound effect).

---

## 5. Perancangan Basis Data (Database Schema)

### 5.1 Relasi Antar Tabel (ERD Concept)

```text
  ┌───────────────┐
  │     users     │ (Super Admin & Guru)
  └───────┬───────┘
          │ 1
          ├─────────────────────────┐
          │ N                       │ N (custom questions)
  ┌───────┴───────┐         ┌───────┴───────┐
  │ game_sessions │         │   questions   │ (Master & Custom)
  └───┬───────┬───┘         └───────┬───────┘
      │ 1     │ 1                   │ 1
      │       │                     │
      │ N     │ N (pivot)           │ N
      │   ┌───┴─────────────────────┼───┐
      │   │ game_session_questions  │   │
      │   └─────────────────────────┘   │
      │                                 │
      │ N                               │
  ┌───┴───────────┐                     │ 
  │  game_scores  │ ────────────────────┘ 
  └───────────────┘
```

### 5.2 Spesifikasi Struktur Tabel

#### **1. Tabel `users`**
Menyimpan akun resmi Super Admin dan Guru.
| Field | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt | Primary Key, Auto Increment | ID Pengguna |
| `name` | String(255) | Not Null | Nama Lengkap Guru/Admin |
| `email` | String(255) | Unique, Not Null | Email Login |
| `password` | String(255) | Not Null | Password Hashed |
| `role` | Enum | `'super_admin'`, `'guru'` | Hak Akses |
| `nama_sekolah`| String(255) | Nullable | Asal Sekolah (Khusus Guru) |
| `timestamps` | Timestamp | Nullable | Created_at & Updated_at |

#### **2. Tabel `game_sessions`**
Menyimpan data kelas/game yang dibuat oleh Guru.
| Field | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt | Primary Key, Auto Increment | ID Sesi Game |
| `user_id` | BigInt | Foreign Key (`users.id`) | Guru Pembuat Game |
| `title` | String(255) | Not Null | Nama Sesi Kelas (misal: "Kelas 1A Ceria") |
| `game_code` | String(50) | Unique, Not Null | Kode Unik (misal: `DETEKTIF-1A`) |
| `is_active` | Boolean | Default: `true` | Status Sesi Game |
| `timestamps` | Timestamp | Nullable | Created_at & Updated_at |

#### **3. Tabel `questions`**
Menyimpan daftar sampah/soal (baik preset default admin maupun buatan kustom guru).
| Field | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt | Primary Key, Auto Increment | ID Soal |
| `user_id` | BigInt | Foreign Key (`users.id`), Nullable | Pembuat Soal (Null jika Master Default Admin) |
| `nama_sampah` | String(255) | Not Null | Nama Objek (misal: "Kulit Pisang") |
| `kategori` | Enum | `'organik'`, `'anorganik'`, `'b3'`| Kunci Jawaban |
| `gambar` | String(255) | Not Null | Path/URL File Gambar |
| `fakta_edukasi`| Text | Nullable | Pesan Edukasi Singkat |
| `is_default` | Boolean | Default: `false` | Menandakan master soal default dari admin |
| `timestamps` | Timestamp | Nullable | Created_at & Updated_at |

#### **4. Tabel Pivot `game_session_questions`**
Menghubungkan Sesi Game dengan Soal yang digunakan, sekaligus mencatat statistik jawaban salah siswa untuk analytics guru.
| Field | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt | Primary Key, Auto Increment | ID Pivot |
| `game_session_id`| BigInt | Foreign Key (`game_sessions.id`)| Sesi Game Terkait |
| `question_id` | BigInt | Foreign Key (`questions.id`) | Soal Terkait |
| `wrong_count` | Integer | Default: `0` | Jumlah berapa kali soal dijawab salah |
| `total_count` | Integer | Default: `0` | Jumlah berapa kali soal ditampilkan |

#### **5. Tabel `game_scores`**
Menyimpan riwayat permainan siswa SD.
| Field | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| `id` | BigInt | Primary Key, Auto Increment | ID Rekam Skor |
| `game_session_id`| BigInt | Foreign Key (`game_sessions.id`)| Sesi Game yang Diikuti |
| `nama_siswa` | String(255) | Not Null | Nama Anak SD |
| `kelas` | String(50) | Nullable | Tingkat Kelas (1, 2, atau 3 SD) |
| `skor_akhir` | Integer | Not Null | Total Poin |
| `jawaban_benar`| Integer | Not Null | Jumlah Sampah Benar |
| `total_sampah` | Integer | Not Null | Total Sampah Diberikan |
| `created_at` | Timestamp | Default: `CURRENT_TIMESTAMP` | Waktu Main |

---

## 6. Kebutuhan Non-Fungsional (Non-Functional Requirements)

### 6.1 Performa & Responsivitas (Performance)
* **Mobile-First Design:** Tampilan dioptimalkan penuh untuk layar smartphone (rasio 16:9 / 20:9) dan tablet.
* **Fast Assets Loading:** Seluruh gambar sampah dikompresi (format WebP/PNG teroptimasi, ukuran < 100 KB per gambar) agar game tetap lancar meski diakses menggunakan jaringan seluler 3G/4G desa.
* **Zero Lag Gameplay:** Logika timer dan acak sampah berjalan 100% di browser (*client-side JavaScript*), sehingga tidak terpapar *latency* server saat anak memilah sampah.

### 6.2 Keamanan & Integritas Data (Security)
* **CORS & CSRF Protection:** Pengiriman skor via `fetch()` dilindungi token CSRF Laravel untuk mencegah manipulasi skor dari luar.
* **Role Middleware:** Halaman dashboard `/admin` dan `/guru` dilindungi middleware autentikasi sehingga siswa tidak bisa menerobos ke halaman guru.

### 6.3 Aksesibilitas (Accessibility)
* **Kombinasi Warna & Ikon:** Pilihan bak sampah tidak hanya dibedakan oleh warna (Hijau, Kuning, Merah), tetapi juga diberi label teks dan ikon pembeda untuk membantu anak dengan gangguan parsial warna (*color blindness*).

---

## 7. Rencana & Langkah Pengembangan (Development Roadmap)

Proses pengerjaan dibagi menjadi **6 Fase Terstruktur** untuk memastikan fondasi aplikasi kuat sebelum membangun game client-side:

### **Fase 1: Database & Model (Fondasi)**
1. **Setup Laravel & Database Configuration**: Inisialisasi proyek dan jalankan migrasi dasar.
2. **Migrations & Models**: Buat migrasi untuk tabel `users`, `game_sessions`, `questions`, `game_session_questions` (pivot dengan kolom counter statistik), dan `game_scores`.
3. **Database Seeders**: Isi database dengan akun Super Admin default, akun Guru demo, dan minimal 15–20 Master Bank Soal sampah default lengkap dengan gambar placeholder dan fakta edukasi singkat.

### **Fase 2: Autentikasi & Dashboard Admin/Guru (Backend CRUD)**
1. **Auth System**: Setup login untuk Guru & Super Admin menggunakan Laravel Breeze atau Jetstream/Laravel Fortify.
2. **Dashboard Super Admin**:
   * CRUD Akun Guru.
   * CRUD Master Bank Soal (menambahkan sampah default dengan upload gambar).
3. **Dashboard Guru**:
   * CRUD Sesi Kelas/Game (misal: "Kelas 1A Ceria").
   * Fitur "Impor Soal Default" untuk memasukkan Master Soal dari Admin ke sesi kelas (mengisi tabel pivot `game_session_questions`).
   * CRUD Soal Kustom (jika guru ingin menambah jenis sampah khusus).

### **Fase 3: QR Code & Link Exporter (Sesi Akses)**
1. **Integrasi Simple QrCode**: Setup package generator QR Code di Laravel.
2. **Halaman Dashboard Guru (Share Mode)**:
   * Tampilan proyektor QR Code besar.
   * Tombol "Salin Link" (`/play/{game_code}`).
   * Export PDF berisi layout stiker QR Code siap print.

### **Fase 4: Game Engine Client-Side (Alpine.js)**
1. **Layar Preloader & Identitas**:
   * Form Input Nama & Pilihan Kelas (Tombol besar 1, 2, 3).
   * Preload gambar sampah dan sound effect agar game berjalan lancar tanpa lag.
2. **Gameplay Arena (Rasio Mobile/Tablet)**:
   * **Logika Adaptif Kelas 1**: Tampilkan hanya **2 Bak Sampah** (Organik & Anorganik), timer 60 detik.
   * **Logika Adaptif Kelas 2-3**: Tampilkan **3 Bak Sampah** (+ B3), timer 45 detik.
   * Logika acak sampah, timer hitung mundur, penambahan skor, dan status Combo Streak menggunakan Alpine.js.
   * Integrasi SFX (Correct/Wrong) menggunakan Audio API / Howler.js.

### **Fase 5: Post-Game, Submit Skor & Dashboard Guru Analytics**
1. **Post-Game Page (Client-Side Review)**:
   * Tampilan skor, gelar detektif cilik, dan fakta edukasi super pendek.
   * **Review Salah**: Tampilkan daftar gambar sampah yang salah dipilah beserta bak yang benar sebagai evaluasi mandiri anak.
2. **Submit Score & Validate**: Kirim skor ke `/api/submit-score` beserta daftar ID sampah yang salah dijawab.
3. **Backend Logic & Counter Update**:
   * Validasi matematika skor di backend (mencegah cheat manipulasi skor dari console).
   * Simpan skor ke tabel `game_scores`.
   * Update statistik counter (`wrong_count` & `total_count`) di tabel pivot `game_session_questions`.
4. **Dashboard Guru Analytics**:
   * Rekap skor siswa secara real-time.
   * **Analisis Miskonsepsi**: Grafik/tabel berisi sampah yang paling sering salah dikelompokkan oleh murid.

### **Fase 6: Integrasi Mode Proyektor & Finishing Touch**
1. **Live Leaderboard (Mode Proyektor)**:
   * Halaman khusus `/play/{game_code}/live` landscape, dengan auto-refresh/polling menampilkan Top 10 secara real-time.
2. **Visual Polishing & Sound Tuning**:
   * Animasi partikel, getaran perangkat saat tap, sound effect lucu.
3. **Export Excel**: Tombol unduh laporan Excel untuk guru dan super admin.
4. **Deploy & Testing**: Uji coba menggunakan HP dan Tablet.

