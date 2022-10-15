<h1>Sistem Informasi Booking Lapangan Futsal</h1>
Project ini dibuat dengan tujuan membantu Anda yang sedang belajar Codeigniter 3 dengan studi kasus pembuatan sistem booking lapangan futsal. Setelah belajar dan memahami project ini Anda dapat membuat project lain atau mengembangkan yang sudah ada supaya lebih baik lagi. Semoga bermanfaat.

<h1>PROJECT INI TIDAK DIIZINKAN UNTUK DIPERJUAL BELIKAN KEPADA SIAPAPUN KECUALI SUDAH ANDA MODIFIKASI</h1>

<h2>Fitur</h2>
<ol>
  <li>Booking lapangan secara online</li>
  <li>Nominal Omset Harian, Bulanan, dan Tahunan</li>
  <li>Grafik/Statistik Omset Bulanan dalam 1 Tahun berjalan</li>
  <li>Total data pada modul album, foto, event, lapangan, kategori, kontak, slider dan customer</li>
  <li>Manajemen Transaksi (generate invoice berdasarkan tahun-bulan-tanggal yang akan reset setiap bulan secara otomatis)</li>
  <li>Manajemen Lapangan</li>
  <li>Manajemen Album dan Foto</li>
  <li>Manajemen Event</li>
  <li>Manajemen Kategori</li>
  <li>Manajemen Slider</li>
  <li>Manajemen Kontak</li>
  <li>Manajemen User dan Customer</li>
  <li>Profil Bisnis</li>
  <li>Set Nominal Diskon Member</li>
</ol>

<h2>Tools</h2>
<ol>
  <li>PHP 7 (7.4.3)</li>
  <li>IonAuth 2</li>
  <li>Codeigniter 3 (3.1.8)</li>
  <li>MySQL</li>
  <li>Bootstrap 3</li>
  <li>AdminLTE 2</li>
</ol>

<h2>Cara Pakai</h2>
Saya asumsikan Anda telah menginstall lampp stack, xampp, atau local development server lainnya. Kalau sudah, silahkan lanjut ke tahapan dibawah ini, namun apabila belum maka bisa ke bagian paling bawah yang ada di README ini.
<ol>
  <li>Silahkan download/clone project ini ke pc/komputer/laptop Anda</li>
  <li>Letakkan di folder htdocs</li>
  <li>Buat database baru di phpmyadmin atau database manager lainnya dengan nama futsal</li>
  <li>Import database yang ada di dalam folder db</li>
  <li>Buka terminal ke direktori project dan jalankan perintah composer update</li>
  <li>Akses ke http://localhost/futsal</li>
</ol>

<h2>Cara Login</h2>
<ol>
  <li>Backend: Sebagai SuperAdmin atau Admin:
    <ul>
      <li>Akses ke http://localhost/futsal/admin/auth/login</li>
      <li>Gunakan akun SuperAdmin dengan email superadmin@gmail.com dan password: superadmin, Admin: administrator@gmail.com dan password: administrator</li>
    </ul>
  </li>
  <li>Frontend: Sebagai Customer Biasa dan Sudah Berlangganan Member
    <ul>
      <li>Akses ke http://localhost/futsal/auth/login</li>
      <li>Gunakan akun biasa dengan email batistuta@gmail.com dan password: asdfghjkl, Admin: userpremium@gmail.com@gmail.com dan password: asdfghjkl</li>
    </ul>
  </li>
</ol>

<h2>Catatan</h2>
<ol>
  <li>Created by Muhammad Azmi - <a href="https://muhazmi.com">muhazmi.com</a> / AmperaKoding - <a href="https://amperakoding.com">amperakoding.com</a></li>
  <li>Sistem membership dilakukan secara manual dengan cara Customer menghubungi SuperAdmin. Kemudian SuperAdmin akan mengganti Tipe User Customer tersebut di backend panel sebagai SuperAdmin.</li>
</ol>

<h2>Cara Install Local Development Server</h2>
Anda bisa menginstall xampp/wampp atau LAMPP Stack di pc/komputer/laptop yang dipakai. Tutorialnya bisa Anda ikuti disini:
<ol>
  <li><a href="https://amperakoding.com/article/cara-install-apache-mysql-dan-php-di-os-linux-lampp">Cara Install Apache, MySQL, dan PHP di OS Linux (LAMPP)</a></li>
  <li><a href="https://www.muhazmi.com/2016/12/cara-install-xampp-yang-baik-dan-benar.html">Cara Install Xampp di OS Linux</a></li>
  <li><a href="https://www.muhazmi.com/2017/08/cara-install-xampp-yang-baik-dan-benar.html">Cara Install Xampp di OS Windows</a></li>
</ol>