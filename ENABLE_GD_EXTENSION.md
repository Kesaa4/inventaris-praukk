# 🔧 Enable GD Extension - REQUIRED untuk QR Code

## ⚠️ Masalah Ditemukan

QR Code library membutuhkan **GD Extension** untuk generate image, tapi extension ini belum enabled di PHP.

**Error:**
```
Unable to generate image: please check if the GD extension is enabled
```

---

## ✅ Solusi: Enable GD Extension

### Langkah 1: Buka php.ini

**File Location:**
```
C:\xampp\php\php.ini
```

**Cara Buka:**
1. Buka File Explorer
2. Navigate ke: `C:\xampp\php\`
3. Klik kanan `php.ini`
4. Open with Notepad atau text editor

---

### Langkah 2: Cari dan Uncomment GD Extension

**Cari baris ini:**
```ini
;extension=gd
```

**Ubah menjadi (hapus semicolon):**
```ini
extension=gd
```

**Cara Cari:**
1. Tekan `Ctrl+F` di Notepad
2. Ketik: `extension=gd`
3. Hapus `;` di depan `extension=gd`
4. Save file (`Ctrl+S`)

---

### Langkah 3: Restart Apache

**Via XAMPP Control Panel:**
1. Buka XAMPP Control Panel
2. Klik tombol "Stop" di Apache
3. Tunggu sampai berhenti
4. Klik tombol "Start" di Apache
5. Tunggu sampai running

**Via Command Line:**
```cmd
# Stop Apache
net stop Apache2.4

# Start Apache
net start Apache2.4
```

---

### Langkah 4: Verify GD Extension Enabled

**Test via Command Line:**
```cmd
php -m | findstr gd
```

**Expected Output:**
```
gd
```

**Test via Browser:**
1. Buat file: `C:\xampp\htdocs\phpinfo.php`
2. Content:
```php
<?php phpinfo(); ?>
```
3. Buka: http://localhost/phpinfo.php
4. Cari "gd" (Ctrl+F)
5. Verify: GD Support = enabled

---

## 🧪 Test QR Code Setelah Enable GD

### Quick Test:
```cmd
php test_qr.php
```

**Expected Output:**
```
Test 1: Class exists?
✅ YES

Test 2: Create QrCode object
✅ QrCode created successfully

Test 3: Generate PNG
✅ PNG generated successfully

Test 4: Get data URI
✅ Data URI: data:image/png;base64,iVBORw0KGgoAAAANS...

🎉 ALL TESTS PASSED!
```

---

### Test di Browser:
```
1. Login sebagai admin
2. Buka: http://localhost:8080/barang
3. Klik tombol QR Code
4. ✅ QR Code muncul!
```

---

## 📋 Troubleshooting

### GD Extension masih tidak muncul?

**Check 1: File php.ini yang benar?**
```cmd
php --ini
```
Pastikan: `Loaded Configuration File: C:\xampp\php\php.ini`

**Check 2: Typo di php.ini?**
- Pastikan: `extension=gd` (bukan `extension=gd2` atau lainnya)
- Pastikan: Tidak ada spasi di depan
- Pastikan: Tidak ada semicolon (`;`)

**Check 3: Apache sudah restart?**
- Stop Apache
- Tunggu 5 detik
- Start Apache
- Check status: Running

**Check 4: PHP version?**
```cmd
php -v
```
GD extension built-in di PHP 7.4+

---

### Alternative: Install GD Extension

Jika GD tidak ada di php.ini, install manual:

**Windows (XAMPP):**
1. GD biasanya sudah included
2. Cek folder: `C:\xampp\php\ext\`
3. Cari file: `php_gd.dll` atau `php_gd2.dll`
4. Jika ada, tambahkan di php.ini:
```ini
extension=gd
```

**Jika file tidak ada:**
1. Download PHP version yang sama
2. Extract `php_gd.dll` dari archive
3. Copy ke `C:\xampp\php\ext\`
4. Enable di php.ini
5. Restart Apache

---

## 🎯 Checklist

### Before:
- [ ] Buka php.ini
- [ ] Cari `extension=gd`
- [ ] Uncomment (hapus `;`)
- [ ] Save file
- [ ] Restart Apache

### Verify:
- [ ] Run: `php -m | findstr gd`
- [ ] Output: `gd`
- [ ] Run: `php test_qr.php`
- [ ] Output: All tests passed

### Test:
- [ ] Login sebagai admin
- [ ] Buka Data Barang
- [ ] Klik tombol QR Code
- [ ] QR Code muncul
- [ ] Download berfungsi
- [ ] Scan berfungsi

---

## 📝 Summary

**Problem:** GD Extension not enabled

**Solution:** 
1. Edit `C:\xampp\php\php.ini`
2. Uncomment `extension=gd`
3. Restart Apache
4. Test QR Code

**Time:** ~2 minutes

**Status:** Easy fix! ✅

---

## 🚀 After Enable GD

### QR Code akan berfungsi:
✅ Generate QR Code
✅ Display di modal
✅ Download sebagai PNG
✅ Scan dengan smartphone

### Semua fitur ready:
✅ Export Excel
✅ QR Code
✅ Mobile Responsive
✅ Batas waktu peminjaman
✅ Riwayat & Statistik
✅ Dan 7 fitur lainnya

**Total: 12 Fitur Complete! 🎉**

---

**Next Step:** Enable GD Extension sekarang! (2 menit)

*File: C:\xampp\php\php.ini*
*Line: extension=gd*
*Action: Uncomment & Restart Apache*
