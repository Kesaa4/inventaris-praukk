# 🧪 Testing Guide - Fitur Baru

## Quick Test Checklist

### ✅ 1. Test Export Excel (5 menit)

#### Test Export Barang
```
1. Login sebagai admin
2. Buka: http://localhost:8080/barang
3. Klik tombol "Export Excel" (hijau, icon Excel)
4. Verify: File "Data_Barang_*.xlsx" terdownload
5. Buka file Excel
6. Check: Data lengkap, header biru, borders rapi
```

**Expected Result:**
- File Excel terdownload
- Format rapi dengan styling
- Data sesuai dengan yang di halaman

#### Test Export Peminjaman
```
1. Login sebagai admin/petugas
2. Buka: http://localhost:8080/pinjam
3. Klik tombol "Export Excel"
4. Verify: File "Data_Peminjaman_*.xlsx" terdownload
5. Check: Kolom keterlambatan ada
```

#### Test Export Activity Log
```
1. Login sebagai admin
2. Buka: http://localhost:8080/activity-log
3. Klik tombol "Export Excel"
4. Verify: File "Activity_Log_*.xlsx" terdownload
```

---

### ✅ 2. Test QR Code (5 menit)

#### Test Generate QR Code
```
1. Login sebagai admin
2. Buka: http://localhost:8080/barang
3. Cari tombol QR Code (icon QR, warna abu-abu)
4. Klik tombol QR Code pada salah satu barang
5. Verify: Modal muncul
6. Verify: Loading spinner muncul
7. Verify: QR Code muncul (300x300px)
8. Verify: Info barang ditampilkan
```

**Expected Result:**
- Modal muncul dengan smooth
- QR Code clear dan readable
- Info barang: kode, jenis, merek

#### Test Download QR Code
```
1. Di modal QR Code
2. Klik tombol "Download QR Code"
3. Verify: File PNG terdownload
4. Check nama file: QR_{kode_barang}.png
5. Buka file PNG
6. Verify: QR Code clear (300x300px)
```

#### Test Scan QR Code
```
1. Buka aplikasi QR Scanner di smartphone
   (Contoh: Google Lens, QR Code Reader)
2. Arahkan kamera ke QR Code di layar/print
3. Verify: Link muncul
4. Tap link
5. Verify: Browser terbuka
6. Verify: Halaman barang dengan filter keyword
7. Verify: Barang yang sesuai ditampilkan
```

**Expected Result:**
- QR Code bisa di-scan
- Link: http://localhost:8080/barang?keyword=BRG001
- Redirect ke halaman barang
- Filter otomatis applied

---

### ✅ 3. Test Mobile Responsive (10 menit)

#### Setup
```
1. Buka browser (Chrome/Firefox/Edge)
2. Tekan F12 (Developer Tools)
3. Tekan Ctrl+Shift+M (Toggle Device Toolbar)
4. Atau klik icon device di toolbar
```

#### Test Mobile (375px - iPhone SE)
```
Device: iPhone SE (375x667)

1. Buka: http://localhost:8080/barang
2. Check: Navbar collapsible
3. Check: Tombol stack vertical
4. Check: Table horizontal scroll
5. Check: Filter form full width
6. Check: Buttons touch-friendly (min 44px)

Expected:
✅ Layout tidak rusak
✅ Semua text readable
✅ Buttons easy to tap
✅ No horizontal overflow
✅ Forms easy to fill
```

#### Test Tablet (768px - iPad)
```
Device: iPad (768x1024)

1. Test semua halaman
2. Check: Layout 2 kolom
3. Check: Navbar expanded
4. Check: Cards side by side
5. Check: Table fit screen

Expected:
✅ Layout optimal untuk tablet
✅ Spacing comfortable
✅ Touch targets adequate
```

#### Test Desktop (1920px)
```
Device: Desktop (1920x1080)

1. Test semua halaman
2. Check: Full layout
3. Check: All features visible
4. Check: Proper spacing

Expected:
✅ Full desktop experience
✅ All columns visible
✅ Optimal spacing
```

#### Test Specific Pages

**Data Barang:**
```
Mobile:
- Filter form: Stack vertical ✅
- Table: Horizontal scroll ✅
- Action buttons: Vertical stack ✅
- QR button: Icon only ✅

Desktop:
- Filter form: Horizontal ✅
- Table: All columns visible ✅
- Action buttons: Horizontal ✅
- QR button: Icon + text ✅
```

**Peminjaman:**
```
Mobile:
- Wide table: Scroll horizontal ✅
- Status badges: Readable ✅
- Date fields: Stack vertical ✅

Desktop:
- All columns visible ✅
- Proper spacing ✅
```

**Forms:**
```
Mobile:
- Input full width ✅
- Labels clear ✅
- Buttons full width ✅
- Easy to fill ✅

Desktop:
- Multi-column layout ✅
- Proper alignment ✅
```

---

### ✅ 4. Test Integration (5 menit)

#### Test QR Code + Mobile
```
1. Buka di mobile view (F12 → Ctrl+Shift+M)
2. Set device: iPhone SE
3. Buka Data Barang
4. Klik tombol QR Code
5. Verify: Modal fit screen
6. Verify: QR Code visible
7. Verify: Download button accessible
```

#### Test Export + Filter
```
1. Buka Data Barang
2. Set filter: Kategori = "Baik"
3. Klik "Export Excel"
4. Buka file Excel
5. Verify: Hanya data kategori "Baik"
```

#### Test All Features Together
```
1. Login sebagai admin
2. Buka Dashboard
3. Check: Statistik muncul
4. Check: Grafik muncul
5. Buka Data Barang
6. Test: Auto filter (ketik di search)
7. Test: Export Excel
8. Test: QR Code
9. Test: Responsive (F12 → Ctrl+Shift+M)
10. Verify: Semua berfungsi
```

---

### ✅ 5. Test Error Handling (3 menit)

#### Test QR Code Error
```
Scenario: Library tidak tersedia (simulasi)

Expected:
- Modal muncul
- Error message: "QR Code library tidak tersedia..."
- Instruksi install ditampilkan
- Aplikasi tidak crash
```

#### Test Export Error
```
Scenario: PhpSpreadsheet tidak tersedia

Expected:
- Export tetap berfungsi
- Fallback ke CSV
- File .csv terdownload
- Data lengkap
```

---

## 📊 Test Results Template

### Export Excel
- [ ] Export Barang: PASS / FAIL
- [ ] Export Peminjaman: PASS / FAIL
- [ ] Export Activity Log: PASS / FAIL
- [ ] Styling correct: PASS / FAIL
- [ ] Data complete: PASS / FAIL

### QR Code
- [ ] Generate QR: PASS / FAIL
- [ ] Download QR: PASS / FAIL
- [ ] Scan QR: PASS / FAIL
- [ ] Modal UI: PASS / FAIL
- [ ] Error handling: PASS / FAIL

### Mobile Responsive
- [ ] Mobile (375px): PASS / FAIL
- [ ] Tablet (768px): PASS / FAIL
- [ ] Desktop (1920px): PASS / FAIL
- [ ] Touch-friendly: PASS / FAIL
- [ ] No overflow: PASS / FAIL

### Integration
- [ ] QR + Mobile: PASS / FAIL
- [ ] Export + Filter: PASS / FAIL
- [ ] All features: PASS / FAIL

---

## 🐛 Common Issues & Solutions

### Issue 1: QR Code tidak muncul
**Symptoms:**
- Modal muncul tapi QR Code tidak muncul
- Error di console

**Solutions:**
```bash
# Check library installed
composer show endroid/qr-code

# Reload autoload
composer dump-autoload

# Check error log
cat writable/logs/log-*.log
```

### Issue 2: Export Excel error
**Symptoms:**
- Download tidak berfungsi
- File corrupt

**Solutions:**
```bash
# Check PHP memory
php -i | grep memory_limit

# Increase if needed (php.ini)
memory_limit = 256M

# Restart web server
```

### Issue 3: Mobile layout rusak
**Symptoms:**
- Horizontal overflow
- Text terlalu kecil
- Buttons terlalu kecil

**Solutions:**
```bash
# Clear browser cache
Ctrl+Shift+Delete

# Hard reload
Ctrl+F5

# Check CSS loaded
F12 → Network → style.css
```

### Issue 4: QR Code tidak bisa di-scan
**Symptoms:**
- Scanner tidak detect
- Link tidak terbuka

**Solutions:**
- Print dengan size minimal 2x2 cm
- Pastikan kontras cukup (hitam-putih)
- Hindari refleksi cahaya
- Update scanner app
- Test dengan scanner app berbeda

---

## 📝 Test Report Template

```
=================================
TEST REPORT - FITUR BARU
=================================

Date: _______________
Tester: _______________
Environment: _______________

EXPORT EXCEL
------------
[ ] Export Barang
[ ] Export Peminjaman
[ ] Export Activity Log
[ ] Styling & Format
[ ] Filter Integration

Notes: _______________

QR CODE
-------
[ ] Generate QR Code
[ ] Download QR Code
[ ] Scan QR Code
[ ] Modal UI/UX
[ ] Error Handling

Notes: _______________

MOBILE RESPONSIVE
-----------------
[ ] Mobile (375px)
[ ] Tablet (768px)
[ ] Desktop (1920px)
[ ] Touch Interaction
[ ] Layout Integrity

Notes: _______________

INTEGRATION
-----------
[ ] QR + Mobile
[ ] Export + Filter
[ ] All Features Together

Notes: _______________

OVERALL RESULT
--------------
Status: PASS / FAIL
Issues Found: _______________
Recommendations: _______________

Signature: _______________
```

---

## 🎯 Success Criteria

### Export Excel
✅ File terdownload dengan benar
✅ Format Excel/CSV valid
✅ Data lengkap dan akurat
✅ Styling professional
✅ Filter applied correctly

### QR Code
✅ QR Code generated successfully
✅ Download berfungsi
✅ QR Code bisa di-scan
✅ Link redirect correct
✅ UI/UX smooth

### Mobile Responsive
✅ Layout tidak rusak di semua device
✅ Touch targets adequate (min 44px)
✅ Text readable (min 14px)
✅ No horizontal overflow
✅ Forms easy to use

### Integration
✅ Semua fitur berfungsi bersamaan
✅ No conflicts
✅ Performance acceptable
✅ Error handling proper

---

## 🚀 Ready for Production?

### Pre-Production Checklist
- [ ] All tests PASS
- [ ] No critical bugs
- [ ] Performance acceptable
- [ ] Security verified
- [ ] Documentation complete
- [ ] User training done
- [ ] Backup strategy ready
- [ ] Rollback plan ready

### Go-Live Checklist
- [ ] Database backup
- [ ] Code deployed
- [ ] Dependencies installed
- [ ] Folders created
- [ ] Permissions set
- [ ] Environment configured
- [ ] Monitoring active
- [ ] Support ready

---

**Happy Testing! 🧪**

*Jika semua test PASS, aplikasi siap untuk production! 🚀*
