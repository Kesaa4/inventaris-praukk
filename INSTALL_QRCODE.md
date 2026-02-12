# Instalasi Library QR Code

## Langkah Instalasi

### 1. Install Library via Composer

Jalankan perintah berikut di terminal/command prompt dari root directory project:

```bash
composer require endroid/qr-code
```

Atau jika sudah update composer.json, jalankan:

```bash
composer update
```

### 2. Buat Folder untuk QR Code

Buat folder untuk menyimpan QR code yang didownload:

**Windows (CMD):**
```cmd
mkdir public\uploads\qrcodes
```

**Linux/Mac:**
```bash
mkdir -p public/uploads/qrcodes
chmod 755 public/uploads/qrcodes
```

### 3. Verifikasi Instalasi

Cek apakah library berhasil terinstall:

```bash
composer show endroid/qr-code
```

Output yang diharapkan:
```
name     : endroid/qr-code
descrip. : Endroid QR Code
versions : * v5.x.x
...
```

### 4. Test Fitur QR Code

1. Login ke aplikasi sebagai **admin**
2. Buka menu **Data Barang**
3. Klik tombol **QR Code** (icon QR) pada salah satu barang
4. Modal akan muncul dengan QR code
5. Klik **Download QR Code** untuk download

### Troubleshooting

#### Error: "Class 'Endroid\QrCode\QrCode' not found"
**Solusi:**
```bash
composer dump-autoload
```

#### Error: Permission denied saat download
**Solusi (Linux/Mac):**
```bash
chmod -R 755 public/uploads/qrcodes
chown -R www-data:www-data public/uploads/qrcodes
```

**Solusi (Windows):**
- Klik kanan folder `public/uploads/qrcodes`
- Properties → Security → Edit
- Berikan Full Control untuk user yang menjalankan web server

#### QR Code tidak muncul
**Solusi:**
1. Cek error log di `writable/logs/`
2. Pastikan library terinstall dengan benar
3. Clear browser cache
4. Coba browser lain

#### Composer tidak ditemukan
**Solusi:**
- Download dan install Composer dari: https://getcomposer.org/download/
- Restart terminal/command prompt setelah install
- Verify dengan: `composer --version`

### Fitur QR Code

Setelah instalasi berhasil, fitur yang tersedia:

1. **Generate QR Code**: Klik tombol QR Code di Data Barang
2. **View QR Code**: Lihat QR code di modal popup
3. **Download QR Code**: Download sebagai file PNG
4. **Scan QR Code**: Scan dengan smartphone untuk akses cepat ke detail barang

### Kegunaan QR Code

- **Labeling Fisik**: Print dan tempel di barang fisik
- **Quick Access**: Scan untuk lihat detail barang
- **Inventory Check**: Verifikasi barang saat peminjaman/pengembalian
- **Asset Tracking**: Tracking lokasi dan status barang

### Catatan

- QR Code hanya tersedia untuk user dengan role **admin**
- QR Code berisi link ke halaman barang dengan filter keyword
- Format file download: `QR_{kode_barang}.png`
- Size QR Code: 300x300 pixels
- Error correction level: High (dapat di-scan meskipun sebagian rusak)

### Support

Jika mengalami masalah, cek:
1. PHP version minimal 8.1
2. Composer version minimal 2.0
3. Extension PHP yang diperlukan: gd atau imagick
4. Memory limit PHP minimal 128M

### Uninstall (jika diperlukan)

Untuk menghapus library QR Code:

```bash
composer remove endroid/qr-code
```

Aplikasi akan tetap berjalan normal, hanya fitur QR Code yang tidak tersedia.
