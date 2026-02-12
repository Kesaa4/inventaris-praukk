# Tasks: Upload Foto Barang

## 1. Database Setup
- [x] 1.1 Buat file SQL untuk alter table barang
- [x] 1.2 Tambah kolom `foto` VARCHAR(255) NULL
- [ ] 1.3 Test migration di database

## 2. Folder Structure Setup
- [x] 2.1 Buat folder `public/uploads/barang/`
- [x] 2.2 Buat folder `public/uploads/placeholder/`
- [x] 2.3 Buat file `.htaccess` di folder uploads/barang
- [x] 2.4 Download/buat placeholder image `no-image.png`
- [x] 2.5 Set permission folder (755)

## 3. Helper Function
- [x] 3.1 Buat file `app/Helpers/upload_helper.php`
- [x] 3.2 Implement function `uploadFotoBarang()`
- [x] 3.3 Implement function `deleteFotoBarang()`
- [x] 3.4 Implement function `getFotoBarang()`
- [ ] 3.5 Test helper functions

## 4. Model Update
- [x] 4.1 Update `BarangModel` allowedFields (tambah 'foto')
- [x] 4.2 Test model dengan field foto

## 5. Controller - Upload Foto (Create)
- [x] 5.1 Update method `store()` di BarangController
- [x] 5.2 Load upload helper
- [x] 5.3 Handle file upload
- [x] 5.4 Validasi file (size, type)
- [x] 5.5 Save foto name ke database
- [x] 5.6 Add activity log
- [ ] 5.7 Test create barang dengan foto
- [ ] 5.8 Test create barang tanpa foto

## 6. Controller - Update Foto (Edit)
- [x] 6.1 Update method `update()` di BarangController
- [x] 6.2 Handle upload foto baru
- [x] 6.3 Hapus foto lama jika upload baru
- [x] 6.4 Update database dengan foto baru
- [x] 6.5 Add activity log
- [ ] 6.6 Test update foto
- [ ] 6.7 Test update tanpa ganti foto

## 7. Controller - Delete Foto
- [x] 7.1 Buat method `deleteFoto($id)` di BarangController
- [x] 7.2 Validasi admin only
- [x] 7.3 Hapus file dari server
- [x] 7.4 Set database foto = NULL
- [x] 7.5 Add activity log
- [x] 7.6 Redirect dengan message
- [ ] 7.7 Test delete foto

## 8. Routes
- [x] 8.1 Tambah route `barang/delete-foto/(:num)`
- [x] 8.2 Test route accessible

## 9. View - List Barang (index.php)
- [x] 9.1 Tambah kolom "Foto" di table
- [x] 9.2 Display thumbnail foto (60x60px)
- [x] 9.3 Buat modal untuk view full size
- [x] 9.4 Add click event untuk modal
- [x] 9.5 Style thumbnail (object-fit: cover)
- [x] 9.6 Test display foto
- [x] 9.7 Test placeholder jika tidak ada foto

## 10. View - Create Barang (create.php)
- [x] 10.1 Tambah input file untuk foto
- [x] 10.2 Set accept attribute (image types)
- [x] 10.3 Tambah info text (format & size)
- [x] 10.4 Buat preview area
- [x] 10.5 Add JavaScript untuk preview
- [x] 10.6 Validasi client-side (size)
- [x] 10.7 Test upload form
- [x] 10.8 Test preview functionality

## 11. View - Edit Barang (edit.php)
- [x] 11.1 Display current foto jika ada
- [x] 11.2 Tambah tombol "Hapus Foto"
- [x] 11.3 Tambah input file untuk upload baru
- [x] 11.4 Buat preview area
- [x] 11.5 Add JavaScript untuk preview
- [x] 11.6 Add confirmation untuk delete
- [x] 11.7 Test display current foto
- [x] 11.8 Test upload foto baru
- [x] 11.9 Test delete foto

## 12. View - History Barang (history.php)
- [ ] 12.1 Display foto barang di header
- [ ] 12.2 Style foto (max-width: 200px)
- [ ] 12.3 Test display

## 13. View - Cetak Detail (cetak_detail.php)
- [ ] 13.1 Display foto barang di cetak
- [ ] 13.2 Style untuk print
- [ ] 13.3 Test print dengan foto

## 14. CSS Styling
- [ ] 14.1 Style thumbnail di list
- [ ] 14.2 Style preview image
- [ ] 14.3 Style modal foto
- [ ] 14.4 Responsive styling
- [ ] 14.5 Hover effect pada thumbnail

## 15. Testing & Validation
- [ ] 15.1 Test upload JPG
- [ ] 15.2 Test upload PNG
- [ ] 15.3 Test upload GIF
- [ ] 15.4 Test upload file >2MB (should fail)
- [ ] 15.5 Test upload non-image (should fail)
- [ ] 15.6 Test delete foto
- [ ] 15.7 Test update foto
- [ ] 15.8 Test placeholder display
- [ ] 15.9 Test modal view
- [ ] 15.10 Test responsive di mobile

## 16. Error Handling
- [ ] 16.1 Handle upload error
- [ ] 16.2 Handle delete error
- [ ] 16.3 Handle permission error
- [ ] 16.4 Display error messages
- [ ] 16.5 Test error scenarios

## 17. Security Check
- [ ] 17.1 Verify .htaccess working
- [ ] 17.2 Test PHP file upload (should be blocked)
- [ ] 17.3 Verify MIME type validation
- [ ] 17.4 Test directory traversal prevention
- [ ] 17.5 Verify admin-only access

## 18. Documentation
- [ ] 18.1 Update README (jika ada)
- [ ] 18.2 Document helper functions
- [ ] 18.3 Add comments di code

## 19. Cleanup & Optimization
- [ ] 19.1 Remove debug code
- [ ] 19.2 Optimize image loading
- [ ] 19.3 Check for orphaned files
- [ ] 19.4 Verify all activity logs

## 20. Final Testing
- [ ] 20.1 Full workflow test (create → upload → view → update → delete)
- [ ] 20.2 Cross-browser testing
- [ ] 20.3 Mobile testing
- [ ] 20.4 Performance testing
- [ ] 20.5 User acceptance testing

## Notes
- Prioritas: Database → Helper → Controller → View
- Test setiap component sebelum lanjut ke next
- Backup database sebelum alter table
- Backup folder uploads secara berkala
