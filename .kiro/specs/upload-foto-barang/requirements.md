# Requirements: Upload Foto Barang

## Overview
Fitur untuk menambahkan dokumentasi visual pada setiap barang inventaris dengan kemampuan upload, view, update, dan delete foto barang.

## User Stories

### 1. Upload Foto Barang (Admin)
**As an** Admin  
**I want to** upload foto untuk setiap barang  
**So that** saya bisa mendokumentasikan kondisi visual barang

**Acceptance Criteria:**
- Admin dapat upload foto saat menambah barang baru
- Admin dapat upload foto saat mengedit barang
- Sistem menerima format: JPG, JPEG, PNG, GIF
- Maksimal ukuran file: 2MB
- Foto disimpan di folder `public/uploads/barang/`
- Nama file di-rename dengan format: `barang_{id}_{timestamp}.{ext}`
- Jika upload foto baru, foto lama otomatis terhapus
- Validasi error jika format atau ukuran tidak sesuai

### 2. View Foto Barang (Semua Role)
**As a** User (Admin/Petugas/Peminjam)  
**I want to** melihat foto barang  
**So that** saya bisa mengetahui kondisi visual barang

**Acceptance Criteria:**
- Foto barang ditampilkan di halaman list barang (thumbnail kecil)
- Foto barang ditampilkan di halaman detail/edit barang (ukuran lebih besar)
- Jika tidak ada foto, tampilkan placeholder image
- Foto bisa diklik untuk melihat ukuran penuh (modal/lightbox)
- Foto ditampilkan di halaman riwayat peminjaman barang
- Foto ditampilkan di cetak detail peminjaman

### 3. Delete Foto Barang (Admin)
**As an** Admin  
**I want to** menghapus foto barang  
**So that** saya bisa menghapus foto yang tidak relevan

**Acceptance Criteria:**
- Admin dapat menghapus foto di halaman edit barang
- Tombol "Hapus Foto" tersedia jika barang memiliki foto
- Konfirmasi sebelum menghapus foto
- File foto terhapus dari server
- Database field `foto` di-set NULL
- Tampilkan placeholder setelah foto dihapus

### 4. Update Foto Barang (Admin)
**As an** Admin  
**I want to** mengganti foto barang  
**So that** saya bisa update dokumentasi visual barang

**Acceptance Criteria:**
- Admin dapat upload foto baru di halaman edit
- Foto lama otomatis terhapus saat upload foto baru
- Preview foto baru sebelum submit
- Validasi sama seperti upload foto baru

## Technical Requirements

### Database Changes
- Tambah kolom `foto` di tabel `barang` (VARCHAR 255, nullable)

### File Structure
```
public/
  uploads/
    barang/
      barang_1_1234567890.jpg
      barang_2_1234567891.png
      ...
    placeholder/
      no-image.png
```

### Validation Rules
- Format: jpg, jpeg, png, gif
- Max size: 2MB (2048 KB)
- Mime type validation
- File extension validation

### Security
- Validate file type (tidak hanya extension)
- Sanitize filename
- Prevent directory traversal
- Restrict upload folder permissions

### Helper Functions
- `uploadFotoBarang($file, $idBarang)` - Upload dan rename foto
- `deleteFotoBarang($filename)` - Hapus foto dari server
- `getPlaceholderImage()` - Return path placeholder image

## UI/UX Requirements

### Form Upload
- Input file dengan label "Foto Barang"
- Preview image setelah select file (before submit)
- Info: "Format: JPG, PNG, GIF. Max: 2MB"
- Button "Pilih Foto" yang user-friendly

### Display Foto
- Thumbnail 100x100px di list barang
- Image 300x300px di form edit
- Lightbox/modal untuk view full size
- Placeholder image jika tidak ada foto

### Responsive
- Foto responsive di semua device
- Touch-friendly untuk mobile

## Out of Scope (Future Enhancement)
- Multiple foto per barang
- Crop/resize foto
- Foto gallery
- Watermark otomatis
- Compress image otomatis

## Success Metrics
- Admin dapat upload foto dengan sukses rate >95%
- Loading time foto <2 detik
- Tidak ada error 404 untuk foto
- Storage usage terpantau

## Dependencies
- CodeIgniter 4 File Upload Library
- GD/Imagick extension (untuk resize jika diperlukan)
- Bootstrap untuk UI
- JavaScript untuk preview image

## Priority
Medium - Penting untuk dokumentasi visual barang
