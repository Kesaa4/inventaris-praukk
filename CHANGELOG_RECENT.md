# Changelog - Recent Updates

## Perubahan Besar: Refactoring Jenis Barang → Kategori

### Database Changes
- Tabel `jenis_barang` diganti menjadi `kategori`
- Field `id_jenis_barang` diganti menjadi `id_kategori`
- Field `nama_jenis_barang` diganti menjadi `nama_kategori`

### File Baru yang Dibuat

#### Controllers
- `app/Controllers/KategoriController.php` - Controller untuk manajemen kategori
  - `index()` - List semua kategori dengan jumlah barang
  - `show($id_kategori)` - Tampilkan barang per kategori
  - `create()` - Form tambah kategori
  - `store()` - Simpan kategori baru
  - `edit($id_kategori)` - Form edit kategori
  - `update($id_kategori)` - Update kategori
  - `delete($id_kategori)` - Hapus kategori

#### Models
- `app/Models/KategoriModel.php` - Model untuk tabel kategori
  - `getAllKategori()` - Ambil semua kategori terurut
  - `getAllKategoriWithCount()` - Ambil kategori dengan jumlah barang
  - `searchKategoriWithCount($search)` - Search kategori
  - `isKategoriExists($nama_kategori, $exclude_id)` - Cek duplikasi
  - `getKondisiStats($id_kategori)` - Statistik kondisi per kategori
  - `getKategoriWithDetails($id_kategori)` - Detail lengkap kategori
  - `getTopKategori($limit)` - Kategori paling banyak digunakan
  - `getEmptyKategori()` - Kategori tanpa barang

#### Views
- `app/Views/kategori/index.php` - Halaman list kategori
- `app/Views/kategori/show.php` - Halaman detail kategori & barang
- `app/Views/kategori/create.php` - Form tambah kategori
- `app/Views/kategori/edit.php` - Form edit kategori

### File yang Diupdate

#### Controllers
- `app/Controllers/BarangController.php`
  - Update semua referensi `jenis_barang` → `kategori`
  - Update `id_jenis_barang` → `id_kategori`
  - Tambah filter kategori di index
  - Update form create/edit dengan dropdown kategori
  - Tambah method `history($id)` - Riwayat peminjaman barang
  - Tambah method `exportExcel()` - Export data barang
  - Tambah method `deleteFoto($id)` - Hapus foto barang

- `app/Controllers/DashboardController.php`
  - Update statistik barang per kategori
  - Tambah chart/grafik kategori
  - Update query JOIN dengan tabel kategori
  - Tambah statistik kondisi barang
  - Tambah peminjaman terbaru
  - Tambah barang populer
  - Tambah user paling aktif

- `app/Controllers/PinjamController.php`
  - Update JOIN dengan tabel kategori
  - Tambah filter dan search
  - Tambah method `requestReturn($id)` - Peminjam ajukan pengembalian
  - Tambah method `returnCheck($id)` - Form cek pengembalian
  - Tambah method `returnUpdate($id)` - Proses pengembalian
  - Tambah method `exportExcel()` - Export data peminjaman
  - Tambah method `cetakDetail($id)` - Cetak detail peminjaman

#### Models
- `app/Models/BarangModel.php`
  - Update field `id_jenis_barang` → `id_kategori`
  - Update method `getBarangWithCategory()` - JOIN ke tabel kategori
  - Update method `getBarangFiltered()` - Filter dengan kategori
  - Tambah method `getByKategori($idKategori)` - Ambil barang per kategori

- `app/Models/PinjamModel.php`
  - Update JOIN dengan tabel kategori
  - Tambah method `getPinjamWithRelasi($userId)` - Ambil data dengan relasi
  - Tambah method `getPinjamWithRelasiById($id)` - Ambil satu data dengan relasi
  - Tambah method `filterPinjam($filters, $userId)` - Filter peminjaman
  - Tambah method `hasActivePinjamForBarang()` - Cek peminjaman aktif
  - Tambah method `hasActivePinjam()` - Cek user punya peminjaman aktif
  - Tambah method `getLatePinjam()` - Ambil peminjaman terlambat
  - Tambah method `countByStatus($status)` - Hitung per status

#### Views
- `app/Views/barang/index.php`
  - Tambah filter kategori dropdown
  - Update tampilan dengan Bootstrap 5
  - Tambah foto barang
  - Tambah link ke riwayat peminjaman

- `app/Views/barang/create.php`
  - Ganti dropdown jenis barang → kategori
  - Tambah upload foto
  - Update validasi form

- `app/Views/barang/edit.php`
  - Ganti dropdown jenis barang → kategori
  - Tambah preview & hapus foto
  - Update validasi form

- `app/Views/barang/history.php` (BARU)
  - Tampilkan riwayat peminjaman per barang
  - Statistik total peminjaman
  - Timeline peminjaman

- `app/Views/barang/trash.php`
  - Update dengan kategori
  - Tambah filter tanggal hapus

- `app/Views/pinjam/index.php`
  - Update tampilan dengan kategori
  - Tambah filter status, tanggal
  - Tambah badge status
  - Tambah info jatuh tempo

- `app/Views/pinjam/create.php`
  - Update dengan kategori
  - Tambah info barang lengkap
  - Validasi barang tersedia

- `app/Views/pinjam/edit.php`
  - Update dengan kategori
  - Tambah form durasi peminjaman
  - Tambah form alasan ditolak

- `app/Views/pinjam/return_check.php` (BARU)
  - Form cek kondisi barang saat pengembalian
  - Input kondisi baik/rusak
  - Keterangan kerusakan

- `app/Views/pinjam/cetak_detail.php` (BARU)
  - Cetak detail peminjaman
  - Format print-friendly

- `app/Views/dashboard/admin.php`
  - Tambah chart peminjaman per bulan
  - Tambah statistik kategori
  - Tambah barang populer
  - Tambah user paling aktif
  - Tambah kondisi barang
  - Tambah peminjaman terbaru

- `app/Views/dashboard/petugas.php`
  - Update dengan kategori
  - Tambah peminjaman menunggu approval
  - Tambah peminjaman aktif

- `app/Views/dashboard/peminjam.php`
  - Update dengan kategori
  - Tambah peminjaman aktif saya
  - Tambah riwayat peminjaman

- `app/Views/dashboard/cetak_laporan.php` (BARU)
  - Cetak laporan peminjaman
  - Filter status dan tanggal
  - Format print-friendly

#### Routes
- `app/Config/Routes.php`
  - Tambah route group untuk kategori
  - Update route peminjaman
  - Tambah route export Excel
  - Tambah route cetak laporan

#### Helpers
- `app/Helpers/upload_helper.php` (BARU)
  - `uploadFotoBarang($file, $idBarang)` - Upload foto
  - `deleteFotoBarang($filename)` - Hapus foto
  - `getFotoBarang($filename)` - Get path foto
  - `validateFotoBarang($file)` - Validasi foto

- `app/Helpers/excel_helper.php` (BARU)
  - `exportToExcel($data, $headers, $filename, $title)` - Export ke Excel

- `app/Helpers/pinjam_helper.php` (BARU)
  - `isLate($tglJatuhTempo, $status, $tglKembali)` - Cek terlambat
  - `hitungHariTerlambat()` - Hitung hari terlambat
  - `sisaWaktu()` - Hitung sisa waktu
  - `getBadgeStatus($status)` - Badge HTML status

- `app/Helpers/user_helper.php` (BARU)
  - `getNamaUser($idUser)` - Ambil nama user
  - `getRoleBadge($role)` - Badge HTML role

- `app/Helpers/activity_helper.php`
  - `log_activity()` - Log aktivitas user
  - `format_log_detail()` - Format log dengan detail

### Fitur Baru

#### 1. Manajemen Kategori
- CRUD kategori barang
- Lihat barang per kategori
- Statistik per kategori
- Validasi kategori tidak boleh duplikat
- Cek kategori digunakan sebelum hapus

#### 2. Upload Foto Barang
- Upload foto saat create/edit barang
- Preview foto
- Hapus foto
- Validasi ukuran max 2MB
- Validasi format JPG, PNG, GIF

#### 3. Riwayat Peminjaman Barang
- Timeline peminjaman per barang
- Statistik total peminjaman
- Info peminjam dan tanggal

#### 4. Export Excel
- Export data barang
- Export data peminjaman
- Filter sebelum export

#### 5. Cetak Laporan
- Cetak detail peminjaman
- Cetak laporan peminjaman
- Filter status dan tanggal

#### 6. Dashboard yang Lebih Lengkap
- Chart peminjaman per bulan
- Barang paling populer
- User paling aktif
- Statistik kondisi barang
- Peminjaman terbaru
- Kategori terbanyak

#### 7. Sistem Pengembalian yang Lebih Baik
- Peminjam ajukan pengembalian
- Petugas cek kondisi barang
- Input kondisi baik/rusak
- Auto update status barang jika rusak

#### 8. Filter & Search yang Lebih Baik
- Filter kategori di barang
- Filter status di peminjaman
- Filter tanggal pengajuan
- Filter tanggal pengembalian
- Search keyword

#### 9. Log Aktivitas yang Lebih Detail
- Log dengan format detail (ringkasan || detail)
- Track perubahan field
- Log export dan cetak

#### 10. Validasi yang Lebih Ketat
- Cek barang tersedia
- Cek user sudah punya peminjaman aktif
- Validasi durasi peminjaman (1-30 hari)
- Validasi kondisi barang

### Perbaikan Bug
- Fix soft delete tidak terfilter di statistik kategori
- Fix status barang tidak update saat peminjaman
- Fix pagination dengan filter
- Fix JOIN query yang tidak efisien

### Peningkatan Performa
- Optimize query dengan proper JOIN
- Reduce N+1 query problem
- Add index pada foreign key (di migration)

### Security Improvements
- Enable CSRF protection
- Input validation di semua form
- Sanitize user input
- Prevent SQL injection dengan query builder

### Code Quality Improvements
- Tambah PHPDoc documentation
- Tambah type hints
- Konsistensi naming convention
- Refactor duplicate code
- Better error handling

---

## Cara Deploy Perubahan Ini

### 1. Database Migration
```sql
-- Rename tabel
RENAME TABLE jenis_barang TO kategori;

-- Rename kolom
ALTER TABLE kategori 
  CHANGE id_jenis_barang id_kategori INT AUTO_INCREMENT,
  CHANGE nama_jenis_barang nama_kategori VARCHAR(100);

-- Update foreign key di tabel barang
ALTER TABLE barang 
  CHANGE id_jenis_barang id_kategori INT;

-- Tambah kolom foto di tabel barang (jika belum ada)
ALTER TABLE barang 
  ADD COLUMN foto VARCHAR(255) NULL AFTER keterangan;
```

### 2. Create Upload Directory
```bash
mkdir public/uploads
mkdir public/uploads/barang
chmod 755 public/uploads
chmod 755 public/uploads/barang
```

### 3. Install Dependencies (jika belum)
```bash
composer install
```

### 4. Update .env
```env
CI_ENVIRONMENT = production
app.baseURL = 'http://your-domain.com/'
```

### 5. Git Commit & Push
```bash
git add .
git commit -m "Major refactor: Jenis Barang → Kategori + New Features

- Refactor jenis_barang to kategori system
- Add photo upload for barang
- Add borrowing history per item
- Add Excel export functionality
- Add print report features
- Improve dashboard with charts and statistics
- Add better return system with condition check
- Add comprehensive filters and search
- Improve activity logging
- Add helper functions
- Enable CSRF protection
- Add PHPDoc documentation
- Add type hints to models
- Fix soft delete filter in statistics
- Optimize database queries"

git push origin main
```

---

## Testing Checklist

### Kategori
- [ ] Create kategori baru
- [ ] Edit kategori
- [ ] Hapus kategori (cek validasi jika ada barang)
- [ ] Lihat barang per kategori
- [ ] Search kategori

### Barang
- [ ] Create barang dengan foto
- [ ] Edit barang dan ganti foto
- [ ] Hapus foto barang
- [ ] Filter barang by kategori
- [ ] Lihat riwayat peminjaman barang
- [ ] Export barang ke Excel
- [ ] Soft delete barang
- [ ] Restore barang dari trash

### Peminjaman
- [ ] Ajukan peminjaman (peminjam)
- [ ] Setujui peminjaman (admin/petugas)
- [ ] Tolak peminjaman dengan alasan
- [ ] Ajukan pengembalian (peminjam)
- [ ] Cek kondisi & setujui pengembalian (admin/petugas)
- [ ] Cek auto update status barang jika rusak
- [ ] Filter peminjaman by status
- [ ] Filter peminjaman by tanggal
- [ ] Export peminjaman ke Excel
- [ ] Cetak detail peminjaman

### Dashboard
- [ ] Cek statistik admin
- [ ] Cek chart peminjaman per bulan
- [ ] Cek barang populer
- [ ] Cek user paling aktif
- [ ] Cek dashboard petugas
- [ ] Cek dashboard peminjam
- [ ] Cetak laporan dengan filter

### Log Aktivitas
- [ ] Cek log create/edit/delete
- [ ] Cek log detail perubahan
- [ ] Cek log export dan cetak
- [ ] Export log ke Excel

---

**Last Updated:** $(date)
**Status:** Ready for Production
