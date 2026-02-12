# Design: Upload Foto Barang

## Architecture Overview

### Component Structure
```
Database (barang table)
    ↓
BarangModel (CRUD + foto handling)
    ↓
BarangController (upload/delete logic)
    ↓
Views (form + display)
    ↓
Public Storage (uploads/barang/)
```

## Database Design

### Table: barang
```sql
ALTER TABLE barang 
ADD COLUMN foto VARCHAR(255) NULL AFTER keterangan;
```

**Field Details:**
- `foto`: Nama file foto (nullable)
- Format: `barang_{id}_{timestamp}.{ext}`
- Example: `barang_1_1707584123.jpg`

## File Structure

### Directory Structure
```
public/
  uploads/
    barang/              # Foto barang
      .htaccess          # Prevent PHP execution
      barang_1_*.jpg
      barang_2_*.png
    placeholder/
      no-image.png       # Default placeholder
```

### .htaccess Content
```apache
# Prevent PHP execution in upload folder
<FilesMatch "\.php$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Allow image files only
<FilesMatch "\.(jpg|jpeg|png|gif)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>
```

## Implementation Design

### 1. Helper Function (app/Helpers/upload_helper.php)

```php
/**
 * Upload foto barang
 * @param object $file - File dari request
 * @param int $idBarang - ID barang
 * @return string|false - Nama file atau false jika gagal
 */
function uploadFotoBarang($file, $idBarang)
{
    // Validasi file
    if (!$file->isValid()) {
        return false;
    }
    
    // Validasi ukuran (max 2MB)
    if ($file->getSize() > 2048000) {
        return false;
    }
    
    // Validasi tipe
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($file->getMimeType(), $allowedTypes)) {
        return false;
    }
    
    // Generate nama file
    $ext = $file->getExtension();
    $newName = 'barang_' . $idBarang . '_' . time() . '.' . $ext;
    
    // Upload
    $file->move(FCPATH . 'uploads/barang', $newName);
    
    return $newName;
}

/**
 * Hapus foto barang dari server
 * @param string $filename - Nama file
 * @return bool
 */
function deleteFotoBarang($filename)
{
    if (empty($filename)) {
        return false;
    }
    
    $path = FCPATH . 'uploads/barang/' . $filename;
    
    if (file_exists($path)) {
        return unlink($path);
    }
    
    return false;
}

/**
 * Get path foto barang atau placeholder
 * @param string|null $filename
 * @return string - URL foto
 */
function getFotoBarang($filename = null)
{
    if ($filename && file_exists(FCPATH . 'uploads/barang/' . $filename)) {
        return base_url('uploads/barang/' . $filename);
    }
    
    return base_url('uploads/placeholder/no-image.png');
}
```

### 2. Controller Changes (BarangController.php)

#### Method: store()
```php
public function store()
{
    // ... existing validation ...
    
    // Handle foto upload
    $foto = null;
    $file = $this->request->getFile('foto');
    
    if ($file && $file->isValid()) {
        helper('upload');
        
        // Insert data dulu untuk dapat ID
        $id = $barangModel->insert([
            // ... existing fields ...
        ]);
        
        // Upload foto
        $fotoName = uploadFotoBarang($file, $id);
        
        if ($fotoName) {
            // Update dengan nama foto
            $barangModel->update($id, ['foto' => $fotoName]);
        }
    } else {
        // Insert tanpa foto
        $id = $barangModel->insert([
            // ... existing fields ...
        ]);
    }
    
    // ... rest of code ...
}
```

#### Method: update()
```php
public function update($id)
{
    // ... existing code ...
    
    helper('upload');
    $barangModel = new BarangModel();
    $old = $barangModel->find($id);
    
    // Handle foto upload
    $file = $this->request->getFile('foto');
    
    if ($file && $file->isValid()) {
        // Hapus foto lama jika ada
        if ($old['foto']) {
            deleteFotoBarang($old['foto']);
        }
        
        // Upload foto baru
        $fotoName = uploadFotoBarang($file, $id);
        
        if ($fotoName) {
            $new['foto'] = $fotoName;
        }
    }
    
    // Update data
    $barangModel->update($id, $new);
    
    // ... rest of code ...
}
```

#### Method: deleteFoto() - NEW
```php
public function deleteFoto($id)
{
    $this->mustAdmin();
    
    helper('upload');
    $barangModel = new BarangModel();
    $barang = $barangModel->find($id);
    
    if (!$barang || !$barang['foto']) {
        return redirect()->back()->with('error', 'Foto tidak ditemukan');
    }
    
    // Hapus file
    if (deleteFotoBarang($barang['foto'])) {
        // Update database
        $barangModel->update($id, ['foto' => null]);
        
        log_activity('Menghapus foto barang: ' . $barang['kode_barang'], 'barang', $id);
        
        return redirect()->back()->with('success', 'Foto berhasil dihapus');
    }
    
    return redirect()->back()->with('error', 'Gagal menghapus foto');
}
```

### 3. View Changes

#### barang/index.php - Add Thumbnail Column
```php
<th>Foto</th>
<!-- ... -->
<td>
    <img src="<?= getFotoBarang($b['foto']) ?>" 
         alt="<?= esc($b['kode_barang']) ?>"
         class="img-thumbnail"
         style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
         data-bs-toggle="modal"
         data-bs-target="#fotoModal<?= $b['id_barang'] ?>">
</td>

<!-- Modal untuk view full size -->
<div class="modal fade" id="fotoModal<?= $b['id_barang'] ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto: <?= esc($b['kode_barang']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="<?= getFotoBarang($b['foto']) ?>" 
                     alt="<?= esc($b['kode_barang']) ?>"
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>
```

#### barang/create.php - Add Upload Field
```php
<div class="mb-3">
    <label class="form-label">Foto Barang</label>
    <input type="file" 
           name="foto" 
           class="form-control" 
           accept="image/jpeg,image/jpg,image/png,image/gif"
           id="fotoInput">
    <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
    
    <!-- Preview -->
    <div id="fotoPreview" class="mt-2" style="display: none;">
        <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
    </div>
</div>

<script>
// Preview image before upload
document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
```

#### barang/edit.php - Add Upload & Delete
```php
<div class="mb-3">
    <label class="form-label">Foto Barang</label>
    
    <!-- Current Photo -->
    <?php if ($barang['foto']): ?>
        <div class="mb-2">
            <img src="<?= getFotoBarang($barang['foto']) ?>" 
                 alt="Current" 
                 class="img-thumbnail" 
                 style="max-width: 200px;">
            <div class="mt-2">
                <a href="<?= base_url('barang/delete-foto/' . $barang['id_barang']) ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Yakin hapus foto ini?')">
                    <i class="bi bi-trash"></i> Hapus Foto
                </a>
            </div>
        </div>
    <?php endif ?>
    
    <!-- Upload New -->
    <input type="file" 
           name="foto" 
           class="form-control" 
           accept="image/jpeg,image/jpg,image/png,image/gif"
           id="fotoInput">
    <small class="text-muted">
        <?= $barang['foto'] ? 'Upload foto baru untuk mengganti' : 'Format: JPG, PNG, GIF. Maksimal 2MB' ?>
    </small>
    
    <!-- Preview -->
    <div id="fotoPreview" class="mt-2" style="display: none;">
        <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
    </div>
</div>

<script>
// Preview image before upload
document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Validate size
        if (file.size > 2048000) {
            alert('Ukuran file maksimal 2MB');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('fotoPreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
```

### 4. Routes (app/Config/Routes.php)
```php
$routes->group('barang', ['filter' => 'admin'], function ($routes) {
    // ... existing routes ...
    $routes->get('delete-foto/(:num)', 'BarangController::deleteFoto/$1');
});
```

### 5. Model Changes (BarangModel.php)
```php
protected $allowedFields = [
    'id_kategori',
    'jenis_barang',
    'merek_barang',
    'tipe_barang',
    'kode_barang',
    'ram',
    'rom',
    'status',
    'keterangan',
    'foto',        // ADD THIS
    'deleted_at'
];
```

## Validation Rules

### File Upload Validation
```php
$validation = \Config\Services::validation();

$validation->setRules([
    'foto' => [
        'label' => 'Foto Barang',
        'rules' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]',
        'errors' => [
            'uploaded' => 'Pilih file foto',
            'max_size' => 'Ukuran foto maksimal 2MB',
            'is_image' => 'File harus berupa gambar',
            'mime_in' => 'Format foto harus JPG, PNG, atau GIF'
        ]
    ]
]);
```

## Error Handling

### Upload Errors
- File too large → "Ukuran foto maksimal 2MB"
- Invalid format → "Format foto harus JPG, PNG, atau GIF"
- Upload failed → "Gagal upload foto, coba lagi"
- Permission denied → "Folder upload tidak memiliki permission"

### Delete Errors
- File not found → "Foto tidak ditemukan"
- Delete failed → "Gagal menghapus foto"

## Security Considerations

1. **File Type Validation**
   - Check MIME type, not just extension
   - Use `finfo_file()` for real file type detection

2. **File Size Limit**
   - Max 2MB per file
   - Check in validation and helper

3. **Filename Sanitization**
   - Use generated name, not original filename
   - Prevent directory traversal

4. **Upload Folder Security**
   - .htaccess to prevent PHP execution
   - Proper folder permissions (755)

5. **Access Control**
   - Only admin can upload/delete
   - All roles can view

## Performance Considerations

1. **Image Optimization**
   - Consider resize on upload (future)
   - Lazy loading for thumbnails
   - CDN for static files (future)

2. **Storage Management**
   - Monitor disk usage
   - Cleanup orphaned files
   - Backup strategy

## Testing Checklist

- [ ] Upload foto saat create barang
- [ ] Upload foto saat edit barang
- [ ] Hapus foto di edit barang
- [ ] View foto di list barang
- [ ] View foto full size (modal)
- [ ] Placeholder jika tidak ada foto
- [ ] Validasi format file
- [ ] Validasi ukuran file
- [ ] Foto lama terhapus saat upload baru
- [ ] Responsive di mobile
- [ ] Preview sebelum upload
- [ ] Error handling

## Future Enhancements

1. Multiple foto per barang
2. Image crop/resize tool
3. Compress image otomatis
4. Watermark
5. Gallery view
6. Drag & drop upload
7. Bulk upload
