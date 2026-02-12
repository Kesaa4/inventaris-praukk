# Fitur QR Code & Mobile Responsive

## 1. QR Code untuk Barang

### Deskripsi
Fitur untuk generate QR Code untuk setiap barang yang dapat di-scan untuk melihat detail barang atau didownload untuk keperluan labeling.

### Implementasi

#### Library
- **endroid/qr-code v5.0**: Library PHP untuk generate QR Code
- Install: `composer require endroid/qr-code`

#### Helper (`app/Helpers/qrcode_helper.php`)
- **generateQRCodeBarang()**: Generate QR Code sebagai base64 data URI
- **saveQRCodeBarang()**: Generate dan simpan QR Code ke file
- **generateQRCodePinjam()**: Generate QR Code untuk peminjaman

#### Controller Methods (`app/Controllers/BarangController.php`)

##### qrcode($id)
- Generate QR Code untuk barang
- Return JSON dengan QR code (base64) dan data barang
- Akses: Semua user yang login

##### downloadQR($id)
- Generate dan download QR Code sebagai file PNG
- Filename: `QR_{kode_barang}.png`
- Akses: Semua user yang login
- Activity log: "Download QR Code barang: {kode_barang}"

#### Routes
```php
$routes->get('barang/qrcode/(:num)', 'BarangController::qrcode/$1');
$routes->get('barang/download-qr/(:num)', 'BarangController::downloadQR/$1');
```

#### UI Features

##### Tombol QR Code
- Icon: `bi-qr-code`
- Warna: btn-secondary
- Posisi: Di kolom aksi barang (hanya untuk admin)
- Responsive: Icon only pada mobile, text + icon pada desktop

##### Modal QR Code
- Loading state dengan spinner
- Display QR code image (300x300px)
- Info barang: kode, jenis, merek
- Tombol download QR code
- Error handling jika library tidak tersedia

#### QR Code Content
- Format: URL ke halaman barang dengan filter keyword
- Contoh: `https://domain.com/barang?keyword=BRG001`
- Error correction: High level
- Size: 300x300px
- Margin: 10px
- Colors: Black foreground, white background

### Kegunaan QR Code
1. **Labeling Fisik**: Print QR code dan tempel di barang
2. **Quick Access**: Scan untuk langsung lihat detail barang
3. **Inventory Check**: Scan untuk verifikasi barang saat peminjaman/pengembalian
4. **Asset Tracking**: Integrasi dengan sistem tracking barang

### Fallback
- Jika library tidak terinstall, tampilkan pesan error dengan instruksi install
- Tidak akan crash aplikasi, hanya fitur QR code yang tidak tersedia

---

## 2. Mobile Responsive Design

### Deskripsi
Perbaikan dan peningkatan responsive design untuk memastikan aplikasi dapat digunakan dengan baik di berbagai ukuran layar (desktop, tablet, mobile).

### Breakpoints
- **Desktop**: > 768px
- **Tablet**: 576px - 768px
- **Mobile**: < 576px

### Implementasi CSS (`public/assets/css/style.css`)

#### Tablet (max-width: 768px)
- Content padding dikurangi: 2rem → 1rem
- Font size header dikurangi: 2rem → 1.5rem
- Table font size: 0.875rem
- Button padding dikurangi
- Navbar brand size dikurangi
- Button group vertical untuk action buttons
- Card spacing improved
- Form label size dikurangi
- Stats cards stack vertically

#### Mobile (max-width: 576px)
- Main content padding: 1rem
- Table padding: 0.75rem 0.5rem
- Table font: 0.8rem
- Hide non-essential table columns (class: d-none-xs)
- Pagination size dikurangi
- Button size extra small
- Modal margin dikurangi
- Action buttons stack vertically
- Navbar links padding increased untuk touch
- Alert padding dikurangi
- Badge size dikurangi

### UI Improvements

#### 1. Action Buttons
- **Desktop**: Horizontal layout dengan text
- **Mobile**: Vertical stack dengan icon only
- Menggunakan Bootstrap classes: `d-md-none`, `d-none d-md-flex`

#### 2. Filter Form
- **Desktop**: Horizontal layout
- **Mobile**: Stack vertically dengan full width
- Auto-adjust spacing dengan `flex-direction: column`

#### 3. Tables
- Horizontal scroll pada mobile
- Reduced padding untuk fit more content
- Optional: Hide non-critical columns dengan class `d-none-xs`

#### 4. Cards
- Full width pada mobile
- Reduced padding
- Better spacing between cards

#### 5. Modals
- Reduced margin pada mobile
- Full width content
- Better touch targets

#### 6. Navigation
- Collapsible navbar pada mobile
- Increased touch target size
- Better dropdown spacing

### Best Practices Applied

1. **Touch-Friendly**
   - Minimum button size: 44x44px
   - Adequate spacing between clickable elements
   - Larger padding untuk touch targets

2. **Content Priority**
   - Most important content visible first
   - Progressive disclosure untuk detail
   - Hide non-essential elements pada mobile

3. **Performance**
   - CSS-only responsive (no JS required)
   - Minimal media queries
   - Efficient selectors

4. **Accessibility**
   - Maintain contrast ratios
   - Readable font sizes
   - Proper heading hierarchy

### Testing Checklist

#### Desktop (> 768px)
- [ ] All features accessible
- [ ] Proper spacing and layout
- [ ] Tables display all columns
- [ ] Action buttons with text labels

#### Tablet (576px - 768px)
- [ ] Content readable and accessible
- [ ] Forms usable
- [ ] Tables scrollable if needed
- [ ] Navigation collapsible

#### Mobile (< 576px)
- [ ] All critical features accessible
- [ ] Touch targets adequate size
- [ ] Forms easy to fill
- [ ] Tables scrollable
- [ ] Buttons stack vertically
- [ ] Modals fit screen
- [ ] Navigation easy to use

### Browser Compatibility
- Chrome/Edge: Full support
- Firefox: Full support
- Safari: Full support
- Mobile browsers: Full support

### Future Improvements
1. Add swipe gestures untuk tables
2. Implement pull-to-refresh
3. Add offline support dengan PWA
4. Optimize images untuk mobile
5. Add lazy loading untuk tables
6. Implement virtual scrolling untuk large datasets

---

## Installation & Setup

### 1. Install QR Code Library
```bash
composer require endroid/qr-code
```

### 2. Create QR Code Directory
```bash
mkdir -p public/uploads/qrcodes
chmod 755 public/uploads/qrcodes
```

### 3. Test QR Code
1. Login sebagai admin
2. Buka halaman Data Barang
3. Klik tombol QR Code pada salah satu barang
4. Verify QR code muncul di modal
5. Test download QR code

### 4. Test Mobile Responsive
1. Buka aplikasi di browser
2. Buka Developer Tools (F12)
3. Toggle device toolbar (Ctrl+Shift+M)
4. Test berbagai ukuran layar:
   - iPhone SE (375px)
   - iPhone 12 Pro (390px)
   - iPad (768px)
   - Desktop (1920px)
5. Verify semua fitur accessible dan usable

---

## File Terkait

### QR Code
- `composer.json` (dependency)
- `app/Helpers/qrcode_helper.php` (helper functions)
- `app/Controllers/BarangController.php` (qrcode, downloadQR methods)
- `app/Config/Routes.php` (routes)
- `app/Views/barang/index.php` (UI)

### Mobile Responsive
- `public/assets/css/style.css` (responsive styles)
- `app/Views/layouts/header.php` (viewport meta)
- All view files (responsive classes)

---

## Troubleshooting

### QR Code tidak muncul
1. Cek apakah library terinstall: `composer show endroid/qr-code`
2. Cek error log: `writable/logs/`
3. Cek permission folder: `public/uploads/qrcodes/`

### Layout rusak di mobile
1. Clear browser cache
2. Cek viewport meta tag di header
3. Verify CSS file loaded
4. Test di browser lain

### Button terlalu kecil di mobile
1. Cek CSS media queries
2. Verify Bootstrap classes
3. Adjust padding di custom CSS
