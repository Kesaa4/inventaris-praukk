# Fitur Export Data ke Excel

## Deskripsi
Fitur untuk mengekspor data barang, peminjaman, dan activity log ke format Excel (.xlsx) atau CSV dengan styling dan formatting yang rapi.

## Implementasi

### 1. Helper Function (`app/Helpers/excel_helper.php`)
- **exportToExcel()**: Export ke Excel menggunakan PhpSpreadsheet (jika tersedia)
- **exportToCSV()**: Fallback ke CSV jika PhpSpreadsheet tidak terinstall
- Fitur styling: header berwarna biru, borders, auto-width columns
- Support UTF-8 dengan BOM untuk karakter Indonesia

### 2. Controller Methods

#### BarangController::exportExcel()
- Export data barang dengan filter (keyword, kategori)
- Kolom: No, Jenis, Merek, Tipe, Kode, RAM, ROM, Kategori, Status, Keterangan
- Akses: Semua user yang login (auth)

#### PinjamController::exportExcel()
- Export data peminjaman dengan filter (keyword, status, tanggal)
- Kolom: No, Peminjam, Email, Barang, Kode, Tgl Pengajuan, Tgl Disetujui, Durasi, Jatuh Tempo, Tgl Dikembalikan, Status, Keterlambatan
- Akses: Admin dan Petugas
- Include perhitungan keterlambatan otomatis

#### ActivityLogController::exportExcel()
- Export activity log dengan filter (keyword, role)
- Kolom: Waktu, Nama User, Email, Role, Aktivitas, Tabel, ID Data
- Akses: Admin only

### 3. Routes (`app/Config/Routes.php`)
```php
// Barang export (auth)
$routes->get('barang/export-excel', 'BarangController::exportExcel');

// Pinjam export (auth)
$routes->get('pinjam/export-excel', 'PinjamController::exportExcel');

// Activity log export (admin)
$routes->get('activity-log/export-excel', 'ActivityLogController::exportExcel');
```

### 4. UI Buttons

#### Barang Index
- Tombol "Export Excel" di card filter
- Icon: `bi-file-earmark-excel`
- Warna: btn-success
- Hanya tampil untuk admin

#### Pinjam Index
- Tombol "Export Excel" di card filter footer
- Icon: `bi-file-earmark-excel`
- Warna: btn-success
- Tampil untuk admin dan petugas

#### Activity Log Index
- Tombol "Export Excel" di card filter
- Icon: `bi-file-earmark-excel`
- Warna: btn-success
- Hanya tampil untuk admin

## Fitur Utama

### 1. Filter Support
- Export menggunakan filter yang sama dengan tampilan halaman
- Parameter filter dikirim via query string
- Data yang diexport sesuai dengan data yang terfilter

### 2. Styling Excel
- Title besar di baris pertama (merged cells, bold, size 16, center)
- Header dengan background biru (#4472C4), text putih, bold, center
- Borders pada semua cell
- Auto-width columns untuk readability
- Format tanggal Indonesia (dd-mm-yyyy HH:mm)

### 3. Activity Log
- Setiap export dicatat di activity log
- Format: "Export data [barang/peminjaman] ke Excel"
- Tabel: barang/pinjam, ID Data: 0

### 4. Fallback Mechanism
- Jika PhpSpreadsheet tidak tersedia, otomatis fallback ke CSV
- CSV dengan UTF-8 BOM untuk support karakter Indonesia
- Filename otomatis berubah dari .xlsx ke .csv

## Filename Format
- Barang: `Data_Barang_YYYY-MM-DD_HHmmss.xlsx`
- Peminjaman: `Data_Peminjaman_YYYY-MM-DD_HHmmss.xlsx`
- Activity Log: `Activity_Log_YYYY-MM-DD_HHmmss.xlsx`

## Dependencies
- PhpSpreadsheet (optional, fallback ke CSV jika tidak ada)
- Helper: excel_helper, pinjam_helper (untuk peminjaman)

## Keamanan
- Role-based access control
- Admin: semua export
- Petugas: barang dan peminjaman
- Peminjam: hanya barang (view only, tidak bisa export)

## Testing
1. Test export dengan filter kosong (semua data)
2. Test export dengan berbagai kombinasi filter
3. Test dengan PhpSpreadsheet installed
4. Test fallback ke CSV tanpa PhpSpreadsheet
5. Test permission (admin, petugas, peminjam)
6. Verify activity log tercatat
7. Verify format tanggal dan data Indonesia

## File Terkait
- `app/Helpers/excel_helper.php`
- `app/Controllers/BarangController.php`
- `app/Controllers/PinjamController.php`
- `app/Controllers/ActivityLogController.php`
- `app/Views/barang/index.php`
- `app/Views/pinjam/index.php`
- `app/Views/activity/index.php`
- `app/Config/Routes.php`
