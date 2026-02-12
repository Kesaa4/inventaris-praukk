# ⚡ Quick Fix - Enable GD Extension (2 Menit)

## 🎯 Masalah

QR Code tidak bisa generate karena **GD Extension belum enabled**.

---

## ✅ Solusi Cepat (3 Langkah)

### 1️⃣ Edit php.ini (30 detik)

**Buka file:**
```
C:\xampp\php\php.ini
```

**Cari baris ini:**
```ini
;extension=gd
```

**Ubah menjadi (hapus semicolon):**
```ini
extension=gd
```

**Save:** Ctrl+S

---

### 2️⃣ Restart Apache (30 detik)

**Via XAMPP Control Panel:**
1. Stop Apache
2. Start Apache

**Via Command:**
```cmd
net stop Apache2.4 && net start Apache2.4
```

---

### 3️⃣ Test (30 detik)

**Command Line:**
```cmd
php -m | findstr gd
```

**Expected:** `gd`

**Browser:**
```
1. Login sebagai admin
2. Buka Data Barang
3. Klik tombol QR Code
4. ✅ QR Code muncul!
```

---

## 🎉 Done!

Setelah enable GD, QR Code akan langsung berfungsi!

**Total Time:** 2 menit
**Difficulty:** Easy ✅

---

## 📚 Detail Lengkap

Baca: `ENABLE_GD_EXTENSION.md` untuk panduan lengkap dan troubleshooting.

---

**File:** C:\xampp\php\php.ini
**Line:** extension=gd
**Action:** Uncomment (hapus `;`)
**Restart:** Apache
**Test:** QR Code di browser
