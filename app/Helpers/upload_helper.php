<?php

/**
 * Upload foto barang
 * 
 * @param object $file - File dari request
 * @param int $idBarang - ID barang
 * @return string|false - Nama file atau false jika gagal
 */
function uploadFotoBarang($file, $idBarang)
{
    // Validasi file
    if (!$file || !$file->isValid()) {
        return false;
    }
    
    // Validasi ukuran (max 2MB = 2048000 bytes)
    if ($file->getSize() > 2048000) {
        return false;
    }
    
    // Validasi tipe MIME
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($file->getMimeType(), $allowedTypes)) {
        return false;
    }
    
    // Generate nama file unik
    $ext = $file->getExtension();
    $newName = 'barang_' . $idBarang . '_' . time() . '.' . $ext;
    
    // Path upload
    $uploadPath = FCPATH . 'uploads/barang';
    
    // Pastikan folder exists
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    
    // Upload file
    try {
        $file->move($uploadPath, $newName);
        return $newName;
    } catch (\Exception $e) {
        log_message('error', 'Upload foto barang gagal: ' . $e->getMessage());
        return false;
    }
}

/**
 * Hapus foto barang dari server
 * 
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
        try {
            return unlink($path);
        } catch (\Exception $e) {
            log_message('error', 'Hapus foto barang gagal: ' . $e->getMessage());
            return false;
        }
    }
    
    return false;
}

/**
 * Get path foto barang atau placeholder
 * 
 * @param string|null $filename - Nama file foto
 * @return string - URL foto atau placeholder
 */
function getFotoBarang($filename = null)
{
    if ($filename && file_exists(FCPATH . 'uploads/barang/' . $filename)) {
        return base_url('uploads/barang/' . $filename);
    }
    
    // Return placeholder SVG
    return base_url('uploads/placeholder/no-image.svg');
}

/**
 * Validasi file foto
 * 
 * @param object $file - File dari request
 * @return array - ['valid' => bool, 'error' => string]
 */
function validateFotoBarang($file)
{
    $result = ['valid' => true, 'error' => ''];
    
    // Cek file ada dan valid
    if (!$file || !$file->isValid()) {
        $result['valid'] = false;
        $result['error'] = 'File tidak valid';
        return $result;
    }
    
    // Cek ukuran (max 2MB)
    if ($file->getSize() > 2048000) {
        $result['valid'] = false;
        $result['error'] = 'Ukuran file maksimal 2MB';
        return $result;
    }
    
    // ✅ Validasi MIME type
    $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $mime = $file->getMimeType();
    if (!in_array($mime, $allowedMimes)) {
        $result['valid'] = false;
        $result['error'] = 'File bukan gambar yang valid';
        return $result;
    }
    
    // Cek extension
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($file->getExtension()), $allowedExt)) {
        $result['valid'] = false;
        $result['error'] = 'Extension file tidak diizinkan';
        return $result;
    }
    
    // ✅ Validasi adalah gambar asli (bukan file PHP yang direname)
    $imageInfo = @getimagesize($file->getTempName());
    if ($imageInfo === false) {
        $result['valid'] = false;
        $result['error'] = 'File bukan gambar yang valid';
        return $result;
    }
    
    return $result;
}
