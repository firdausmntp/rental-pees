# 🎮 Rental PS Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.37.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.3.11-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/Livewire-3.0-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/SQL_Server-2019-CC2927?style=for-the-badge&logo=microsoft-sql-server&logoColor=white" alt="SQL Server">
</p>

## 📋 Tentang Aplikasi

**Rental PS Management System** adalah aplikasi web modern untuk manajemen rental PlayStation yang komprehensif. Aplikasi ini dirancang untuk mempermudah pengelolaan rental PS dengan fitur-fitur lengkap mulai dari transaksi, voucher, hingga pelaporan.

### ✨ Fitur Utama

#### 👨‍💼 Panel Owner
- **Dashboard Analytics** - Visualisasi data pendapatan, transaksi, dan statistik rental
- **Manajemen PlayStation** - CRUD lengkap untuk konsol PS (PS3, PS4, PS5)
- **Manajemen Tarif** - Pengaturan harga per jam untuk setiap tipe PlayStation
- **Manajemen Member** - Kelola data member dan pelanggan
- **Manajemen Voucher** - Buat, approve, dan monitor voucher pembelian
- **Laporan Transaksi** - Export Excel dengan filter tanggal dan analisis pelanggan teraktif
- **Live Monitoring** - Pantau PS yang sedang digunakan secara real-time

#### 👨‍💻 Panel Karyawan
- **Transaksi Sewa** - Proses sewa PS dengan/tanpa voucher
- **Transaksi Kembali** - Proses pengembalian dan perhitungan biaya
- **Redeem Voucher** - Validasi dan gunakan voucher member
- **Approve Voucher** - Konfirmasi pembayaran voucher pending

#### 👤 Panel Member
- **Dashboard Member** - Info voucher dan riwayat transaksi
- **Beli Voucher** - Pembelian voucher online (Cash/QRIS)
- **Jadwal PS** - Lihat ketersediaan PlayStation
- **Upload Bukti Bayar** - Upload bukti transfer untuk voucher QRIS

### 🎯 Workflow Voucher

```
Member Beli Voucher (Status: pending, Kode: NULL)
           ↓
Upload Bukti Pembayaran (jika QRIS)
           ↓
Kasir Approve Pembayaran
           ↓
Status: aktif, Generate Kode: VCH-XXXXXX
           ↓
Redeem Voucher di Kasir
           ↓
Status: terpakai, Link ke Transaksi
```

## 🚀 Tech Stack

- **Backend Framework**: Laravel 12.37.0
- **Frontend**: Livewire 3.0 + Alpine.js
- **UI Framework**: DaisyUI + Tailwind CSS
- **Database**: SQL Server 2019
- **Icons**: BoxIcons
- **Excel Export**: Maatwebsite Excel 3.1
- **Authentication**: Laravel Breeze (Modified)

## 📦 Persyaratan Sistem

- PHP >= 8.3.11
- Composer
- SQL Server 2019 atau lebih baru
- ODBC Driver 17 for SQL Server
- Node.js & NPM (untuk asset compilation)
- Web Server (Apache/Nginx)

## 🛠️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/firdausmntp/rental-pees.git
cd rental-pees
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

**Local SQL Server:**
```env
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=rental_pees
DB_USERNAME=sa
DB_PASSWORD=YourPassword
DB_TRUST_SERVER_CERTIFICATE=true
```

**Remote SQL Server (dengan instance name):**
```env
DB_CONNECTION=sqlsrv
DB_HOST=sql.bsite.net\MSSQL2016
DB_PORT=
DB_DATABASE=ripron_rentalpees
DB_USERNAME=ripron_rentalpees
DB_PASSWORD=oke123
DB_TRUST_SERVER_CERTIFICATE=true
DB_ENCRYPT=no
```

> **Important Notes:**
> - Jika host menggunakan instance name (contoh: `server\INSTANCE`), gunakan **single backslash** tanpa quotes
> - **PORT dikosongkan** untuk named instance (biarkan `DB_PORT=`)
> - Set `DB_ENCRYPT=no` jika server tidak support encryption
> - Set `DB_TRUST_SERVER_CERTIFICATE=true` untuk bypass SSL verification

### 5. Konfigurasi Email (SMTP)

Setup konfigurasi email untuk testing forgot password di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.ethereal.email
MAIL_PORT=587
MAIL_USERNAME=shanon.bernhard@ethereal.email
MAIL_PASSWORD=TeqxVUvmz6AHDEVS6w
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@rental-ps.com"
MAIL_FROM_NAME="Rental PS Management"
```

> **Note**: Konfigurasi di atas menggunakan [Ethereal Email](https://ethereal.email/) untuk testing. Untuk production, gunakan SMTP provider seperti Gmail, SendGrid, atau Mailgun.

### 6. Database Migration & Seeding

```bash
# Run migrations
php artisan migrate

# Run seeders (creates default users, tarifs, and PlayStation data)
php artisan db:seed

# Or seed specific seeder only
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=TarifSeeder
php artisan db:seed --class=PlayStationSeeder

# Fresh install (drop all tables and reseed)
php artisan migrate:fresh --seed
```

> **Note**: Jika ada error duplicate key saat seeding, jalankan seeder spesifik saja atau gunakan `migrate:fresh --seed`

### 7. Storage Link

```bash
php artisan storage:link
```

### 8. Compile Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Run Application

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://127.0.0.1:8000`

### 10. Testing Email (Optional)

Untuk test forgot password feature:

1. Akses halaman forgot password
2. Masukkan email user yang terdaftar
3. Check inbox di [Ethereal Email](https://ethereal.email/messages)
4. Login dengan credentials SMTP di atas untuk melihat email yang terkirim

## 👥 Default User Credentials

Setelah seeding, gunakan kredensial berikut:

| Role | Email | Password |
|------|-------|----------|
| Owner | owner@rental-ps.com | owner123 |
| Karyawan | karyawan@rental-ps.com | karyawan123 |
| Member | member@rental-ps.com | member123 |

## 📁 Struktur Database

### Tabel Utama

- **users** - Data pengguna (owner, karyawan, member)
- **play_stations** - Data konsol PlayStation
- **tarifs** - Tarif sewa per jam
- **transaksis** - Transaksi sewa dan pengembalian
- **vouchers** - Voucher pembelian member
- **pelanggans** - Data pelanggan non-member
- **members** - Data tambahan member

### Relasi Penting

```
Transaksi → PlayStation (many-to-one)
Transaksi → Tarif (many-to-one)
Transaksi → User/Pelanggan (polymorphic)
Voucher → Member (many-to-one)
Voucher → Tarif (many-to-one)
Voucher → Transaksi (one-to-one, nullable)
```

## 🎨 Fitur UI/UX

- **Responsive Design** - Mobile-first approach
- **Dark/Light Mode** - Toggle tema dengan localStorage
- **Real-time Updates** - Livewire reactive components
- **Toast Notifications** - Feedback visual untuk setiap aksi
- **Smooth Animations** - Transitions menggunakan Alpine.js
- **Sidebar Collapsible** - Maximize workspace area
- **Image Modal** - Preview bukti pembayaran full screen

## 📊 Laporan & Export

### Laporan Transaksi
- Filter by tanggal (dari-sampai)
- Filter by status PS
- Export to Excel (.xlsx)
- Data pelanggan teraktif
- Total pendapatan per periode

### Format Export Excel
- Sheet 1: Data transaksi lengkap
- Kolom: Kode, Pelanggan, PS, Tarif, Durasi, Total, Status, Tanggal
- Auto-formatting untuk currency dan datetime

## 🔒 Security Features

- CSRF Protection
- SQL Injection Prevention (Eloquent ORM)
- XSS Protection
- Role-based Access Control
- Password Hashing (bcrypt)
- Session Management
- File Upload Validation

## 🐛 Troubleshooting

### SQL Server Connection Issues

```bash
# Install ODBC Driver 17 for SQL Server
# Windows: Download dari Microsoft
# Linux: sudo apt-get install msodbcsql17
```

**Common Issues:**

1. **Named Instance Format**:
   - ✅ Correct: `DB_HOST=server\INSTANCE` (single backslash, no quotes, no port)
   - ❌ Wrong: `DB_HOST="server\\INSTANCE"` atau `DB_HOST=server,1433`
   - Leave `DB_PORT=` empty for named instances!

2. **Timeout Errors**:
   ```
   TCP Provider: The wait operation timed out
   ```
   - Check format: Use `server\instance` NOT `server,port`
   - Verify server accessible: `ping server-name`
   - Firewall might be blocking

3. **SSL/Encryption Issues**:
   - Set `DB_ENCRYPT=no` if server doesn't support encryption
   - Set `DB_TRUST_SERVER_CERTIFICATE=true` for self-signed certs

**Test Connection:**
```bash
# Clear cache terlebih dahulu
php artisan config:clear

# Test dengan migrate status
php artisan migrate:status

# Atau test langsung dengan tinker
php artisan tinker
>>> DB::connection()->getPdo();
```

**Native PHP Test Script**:
```php
<?php
$conn = sqlsrv_connect("server\INSTANCE", [
    "Database" => "dbname",
    "Uid" => "username",
    "PWD" => "password",
    "TrustServerCertificate" => true
]);
if ($conn) echo "Connected!";
else print_r(sqlsrv_errors());
```

### Storage Permission

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows: Ensure IIS/Apache has write permission
```

### Migration Errors

```bash
# Rollback and re-run
php artisan migrate:fresh

# With seeding
php artisan migrate:fresh --seed
```

### Email Not Sending

```bash
# Check queue if using queue driver
php artisan queue:work

# Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });

# Clear config cache
php artisan config:clear
```

**Common Issues:**
- Pastikan firewall tidak block port 587
- Untuk production, ganti Ethereal dengan SMTP provider real
- Check credentials SMTP di `.env` sudah benar
- Pastikan `MAIL_FROM_ADDRESS` valid

## 📝 Development Notes

### Email Configuration
- **Testing**: Menggunakan Ethereal Email (fake SMTP untuk development)
- **Production**: Ganti dengan SMTP provider real (Gmail, SendGrid, Mailgun, etc.)
- **Features**: Password reset, notification emails
- **View Sent Emails**: Login ke https://ethereal.email/messages dengan credentials SMTP
- **Queue**: Gunakan `QUEUE_CONNECTION=database` dan jalankan `php artisan queue:work` untuk async email

### Voucher System
- Status: `pending`, `aktif`, `terpakai`, `expired`
- Kode voucher: Generated saat approve (format: `VCH-XXXXXXXX`)
- Pending vouchers: `kode_voucher = NULL`
- Payment methods: `cash`, `qris`
- Payment methods: `cash`, `qris`

### PlayStation Status
- `Tersedia` - Ready untuk sewa
- `Sedang Digunakan` - Aktif dalam transaksi
- `Maintenance` - Sedang perbaikan

### Transaction Flow
1. Create transaksi (status: `Sedang Digunakan`)
2. PS status updated to `Sedang Digunakan`
3. Return transaksi (calculate duration & cost)
4. PS status back to `Tersedia`

## 🚀 Production Deployment

### Remote Database Setup (bsite.net / Smart ASP.NET)

1. **Database Configuration**:
   ```env
   DB_CONNECTION=sqlsrv
   DB_HOST=sql.bsite.net\MSSQL2016
   DB_PORT=
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   DB_TRUST_SERVER_CERTIFICATE=true
   DB_ENCRYPT=no
   ```

2. **Run Migrations**:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

3. **Optimize for Production**:
   ```bash
   # Cache configuration
   php artisan config:cache
   
   # Cache routes
   php artisan route:cache
   
   # Cache views
   php artisan view:cache
   
   # Build assets
   npm run build
   ```

4. **Environment Variables**:
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Update `APP_URL` ke domain production
   - Ganti SMTP dari Ethereal ke provider real

### Seeded Data Summary

Setelah seeding, data berikut akan tersedia:

**Users:**
- Owner: `owner@rental-ps.com` / `owner123`
- Karyawan: `karyawan@rental-ps.com` / `karyawan123`
- Member: `member@rental-ps.com` / `member123`

**Tarifs:**
- PS3: Rp 5.000/jam
- PS4: Rp 8.000/jam
- PS5: Rp 12.000/jam

**PlayStation:** 7 units (PS3, PS4, PS5 dengan berbagai lokasi dan stik)

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

Untuk pertanyaan atau dukungan, hubungi:
- Email: rental@example.com
- GitHub Issues: [Create New Issue](https://github.com/firdausmntp/rental-pees/issues)
- Repository: [https://github.com/firdausmntp/rental-pees](https://github.com/firdausmntp/rental-pees)

---

<p align="center">Made with ❤️ using Laravel & Livewire</p>
