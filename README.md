Emlak Sitesi Projesi
Bu proje, emlakçıların veya bireysel kullanıcıların gayrimenkul ilanlarını dijital ortamda yönetebilmeleri için PHP ve SQL (MySQL) teknolojileri kullanılarak geliştirilmiş web tabanlı bir uygulamadır.

🚀 Özellikler
İlan Yönetimi: İlan ekleme, silme ve güncelleme işlemleri.

Dinamik Filtreleme: Kategoriye (Satılık/Kiralık), konuma ve fiyat aralığına göre arama.

Yönetim Paneli: Site içeriklerini ve gelen ilanları yönetmek için admin paneli.

Veritabanı Entegrasyonu: MySQL ile optimize edilmiş hızlı veri çekme işlemleri.

Responsive Tasarım: Mobil ve masaüstü cihazlarla tam uyumlu arayüz.

🛠 Kullanılan Teknolojiler
Backend: PHP

Database: MySQL / MariaDB

Frontend: HTML5, CSS3, Bootstrap (veya JavaScript/jQuery)

Server: Localhost (XAMPP/WAMP)


emlak sitesi/
├── index.php
├── property_detail.php
├── search.php
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   └── dashboard.css
│   └── js/
│       └── main.js
├── auth/
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── register_process.php
│   └── login_process.php
├── config/
│   └── db.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── auth_check.php
├── panels/
│   ├── admin/
│   │   ├── index.php
│   │   ├── users.php
│   │   └── listings.php
│   ├── seller/
│   │   ├── index.php
│   │   ├── add_listing.php
│   │   └── my_listings.php
│   └── buyer/
│       ├── index.php
│       └── favorites.php
└── public/
    └── uploads/
        └── properties/
