## - [Intro](#intro)
## - [Instalasi Dan Konfigurasi](#instalasi-dan-konfigurasi)
## - [Kontributor](#Kontributor)
## - [Source](#source)

# [Intro](#intro)
Web Apotek Jati Negara adalah sebuah website yang dibuat untuk memenuhi kewajiban tugas besar matakuliah Manajemen Sistem Basis Data (Teknologi Informasi, Universitas Sumatera Utara).  
Apotek Jati Negara adalah aplikasi apotek online yang memungkinkan pengguna untuk dengan mudah mencari dan memesan obat-obatan secara praktis dari rumah mereka. Dengan antarmuka yang ramah pengguna dan fitur-fitur inovatif, aplikasi ini bertujuan untuk menyederhanakan proses pembelian obat dan meningkatkan kenyamanan pengguna serta mempermudah pihak apotek untuk mengontrol data data dari apotek.  
  
[NB: Apotek Jati Negara adalah sebuah apotek yang berlokasi di Medan, data dan informasi yang ada telah disamarkan]

# Instalasi Dan Konfigurasi
- ## Tech
   #### - [Laravel 10]
   #### - [Tailwind CSS]
   #### - [Laravel Breeze]
   #### - [Laravel Livewire]

 - ## Instalasi
   - #### Lakukan Clone pada Github Repositori ini
        - Klik tombol "Code" (berwarna hijau) untuk mendapatkan URL repository. Jika menggunakan HTTPS, salin URL tersebut. Jika menggunakan SSH, klik ikon SSH dan salin URL SSH.
        - Buka terminal, command prompt atau Git Bash(rekomendasi) di komputer Anda.
        - Pindah ke direktori di mana Anda ingin menyimpan salinan lokal repository. Gunakan perintah cd untuk berpindah ke direktori tersebut.
          #### Contoh:
              cd path/ke/direktori/tujuan
        - Gunakan perintah git clone dengan menyertakan URL repository yang telah Anda salin sebelumnya.
          #### Contoh untuk HTTPS:
              git clone https://github.com/nama-akun/nama-repo.git
          #### Atau untuk SSH:
              git clone git@github.com:nama-akun/nama-repo.git
   - #### Jalankan Di Code Editor
       - Buka Terminal di direktori penyimpanan project.
   - #### Install Dependensi
     #### - Jalankan perintah berikut:
         composer install
     #### - Selanjutnya, jalankan perintah berikut:
         npm install
   - #### Buat Salinan File Konfigurasi
     - Salin file `.env.example` dan beri nama baru menjadi `.env`
       #### Jalankan Perintah Berikut:
           cp .env.example .env
   - #### Konfigurasi file `.env`
     - Buka file `.env` dan konfigurasi pengaturan database, koneksi email, dan login google.
       ### Pengaturan database
       #### memungkinkan penggunaan akun database mysql yang berbeda saat login dengan user privilege yang berbeda:
           # Koneksi database menggunakan root
             DB_HOST=127.0.0.1
             DB_PORT=3306
             DB_DATABASE=apotekjatinegara
             DB_USERNAME=root
             DB_PASSWORD=
           # Akhir koneksi database menggunakan root
            
           # Koneksi database menggunakan customer
            DB_HOST_CUSTOMER=127.0.0.1
            DB_PORT_CUSTOMER=3306
            DB_DATABASE_CUSTOMER=apotekjatinegara
            DB_USERNAME_CUSTOMER=root
            DB_PASSWORD_CUSTOMER=
           # Akhir koneksi database menggunakan customer
            
           # Koneksi database menggunakan cashier
            DB_HOST_CASHIER=127.0.0.1
            DB_PORT_CASHIER=3306
            DB_DATABASE_CASHIER=apotekjatinegara
            DB_USERNAME_CASHIER=root
            DB_PASSWORD_CASHIER=
           # Akhir koneksi database menggunakan cashier
            
           # Koneksi database menggunakan owner
            DB_HOST_OWNER=127.0.0.1
            DB_PORT_OWNER=3306
            DB_DATABASE_OWNER=apotekjatinegara
            DB_USERNAME_OWNER=root
            DB_PASSWORD_OWNER=
           # Akhir koneksi database menggunakan owner
         
       ### Pengaturan Mail
       #### Ubah sesuai kebutuhan:
            MAIL_MAILER=smtp
            MAIL_HOST=Host yang digunakan
            MAIL_PORT=Post mail
            MAIL_USERNAME=Username
            MAIL_PASSWORD=Password
            MAIL_ENCRYPTION=null
            MAIL_FROM_ADDRESS="apotekjatinegara@gmail.com"
            MAIL_FROM_NAME="Apotek Jati Negara"
       [NB: tutorial menggunakan mailtrap (https://www.youtube.com/watch?v=OXqDlufizG8) (09:09 - 13:55)]
         
       ### Pengaturan Google
       #### Isi sesuai data yang dimiliki:
            GOOGLE_CLIENT_ID= Client_ID
            GOOGLE_CLIENT_SECRET= Client_Secret
       [NB: tutorial menggunakan log in with google (https://www.youtube.com/watch?v=XyMU2LJIZe8) (00:47 - 03:17)]
   - #### Generate Application Key
     #### Jalankan perintah berikut di terminal:
         php artisan key:generate
   - #### Jalankan Migrasi dan Seeder
     Jalankan perintah migrasi untuk membuat struktur table
     #### jalankan perintah berikut:
         php artisan migrate
     Jalankan perintah seeder untuk mengisi data pada table dengan data dummy
     #### jalankan perintah berikut:
         php artisan db:seed
   - #### Jalankan Server Lokal
     #### jalankan perintah berikut:
         php artisan serve
     #### lalu
         php artisan storage:link
     #### dan
         npm run dev
   - #### Buka Proyek di Browser
      Buka browser dan kunjungi alamat yang ditampilkan di terminal. Biasanya, ini adalah `http://127.0.0.1:8000`.

     #### NB:
         Username: -cek dimysql-
         Password: password

# [Kontributor](#kontributor)
- ### Alwin Liufandy
  - Instagram: @winz.liu
  - LinkedIn: Alwin Liufandy
- ### Devandra Deal Fatahilla
  - Instagram: @devandradealf
  - LinkedIn: Devandra Deal
- ### Jessindy Tanuwijaya
  - Instagram: @jessindytanu
  - LinkedIn: Jessindy Tanuwijaya
- ### Julyant Anggara
  - Instagram: @julyantanggara
  - LinkedIn: Julyant Anggara
- ### Muhammad Raihan Abdillah Lubis
  - Instagram: @rrreyabd
  - LinkedIn: Muhammad Raihan Abdillah Lubis
- ### Yohana Septamia
  - Instagram: @yohanasept_
  - LinkedIn: Yohana Septamia

# [Source](#source)
- ## Apotek Jati Negara
  https://maps.app.goo.gl/G7Kpsg474Jd3uFDR6 (Maps)
- ## [Laravel 10](#laravel-10)
  https://laravel.com/
- ## [Tailwind CSS](#tailwind-css)
  https://tailwindcss.com/
- ## [Laravel Breeze](#laravel-breeze)
  https://laravel.com/docs/10.x/starter-kits#breeze-and-livewire
- ## [Laravel Livewire](#laravel-livewire)
  https://laravel-livewire.com/
