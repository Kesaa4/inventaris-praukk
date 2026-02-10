-- Tambah kolom foto di tabel barang
-- Fitur: Upload Foto Barang
-- Date: 2026-02-10

ALTER TABLE barang 
ADD COLUMN foto VARCHAR(255) NULL AFTER keterangan;

-- Keterangan:
-- foto: Nama file foto barang (nullable)
-- Format: barang_{id}_{timestamp}.{ext}
-- Example: barang_1_1707584123.jpg
