<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangModel;
use App\Models\KategoriModel;

class BarangController extends BaseController
{
    // Tampilkan daftar barang
    public function index()
    {
        // Inisialisasi model
        $barangModel   = new BarangModel();
        $kategoriModel = new KategoriModel();

        // Ambil parameter filter dari query string
        $keyword  = $this->request->getGet('keyword');
        $kategori = $this->request->getGet('kategori');

        // AMBIL barangModel FILTER
        $barangModel->getBarangFiltered($keyword, $kategori);

        // PAGINATE DARI barangModel
        $data = [
            'title'     => 'Data Barang',
            'barang'    => $barangModel->paginate(10, 'barang'),
            'pager'     => $barangModel->pager,
            'kategori'  => $kategoriModel->findAll(),
            'keyword'   => $keyword,
            'catFilter' => $kategori,
        ];

        // Tampilkan view dengan data
        return view('barang/index', $data);
    }

    // Cek apakah user adalah admin
    private function mustAdmin()
    {
        if (session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    // Tampilkan form tambah barang
    public function create()
    {
        // Cek admin
        $this->mustAdmin();

        // Inisialisasi model
        $kategoriModel = new KategoriModel();
        $barangModel = new BarangModel();

        // Tampilkan view dengan data kategori
        return view('barang/create', [
            'kategori' => $kategoriModel->findAll(),
            'jenis_barang' => $barangModel->getEnumJenisBarang()
        ]);
    }

    // Simpan data barang baru
    public function store()
    {
        // Cek admin
        $this->mustAdmin();
        
        // Inisialisasi model
        $barangModel = new BarangModel();

        // Ambil kode barang dari form
        $kodeBarang = $this->request->getPost('kode_barang');

        // Simpan data
        $id = $barangModel->insert([
            'jenis_barang'  => $this->request->getPost('jenis_barang'),
            'merek_barang'  => $this->request->getPost('merek_barang'),
            'tipe_barang'   => $this->request->getPost('tipe_barang'),
            'kode_barang'   => $kodeBarang,
            'ram'           => $this->request->getPost('ram'),
            'rom'           => $this->request->getPost('rom'),
            'status'        => $this->request->getPost('status'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'id_kategori'   => $this->request->getPost('id_kategori')
        ]);

        // Log pakai kode barang
        log_activity(
            'Menambah barang: '.$kodeBarang,
            'barang',
            $id
        );

        // Redirect dengan pesan sukses
        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan');
    }

    // Tampilkan form edit barang
    public function edit($id)
    {
        // Cek admin
        $this->mustAdmin();

        // Inisialisasi model
        $barangModel = new BarangModel();
        $kategoriModel = new KategoriModel();

        // Tampilkan view dengan data barang dan kategori
        return view('barang/edit', [
            'barang'    => $barangModel->find($id),
            'kategori'  => $kategoriModel->findAll(),
            'jenis_barang' => $barangModel->getEnumJenisBarang()
        ]);
    }

    // Update data barang
    public function update($id)
    {
        // Cek admin
        $this->mustAdmin();

        // Inisialisasi model
        $barangModel = new BarangModel();

        // Data lama
        $old = $barangModel->find($id);
        if (!$old) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Data baru
        $new = [
            'jenis_barang' => $this->request->getPost('jenis_barang'),
            'id_kategori'  => $this->request->getPost('id_kategori'),
            'merek_barang' => $this->request->getPost('merek_barang'),
            'tipe_barang'  => $this->request->getPost('tipe_barang'),
            'kode_barang'  => $this->request->getPost('kode_barang'),
            'ram'          => $this->request->getPost('ram'),
            'rom'          => $this->request->getPost('rom'),
            'status'       => $this->request->getPost('status'),
            'keterangan'   => trim($this->request->getPost('keterangan') ?? '')
        ];

        // Update data
        $barangModel->update($id, $new);

        // Bandingkan perubahan
        $changes = [];

        // Inisialisasi model kategori untuk nama kategori
        $kategoriModel = new KategoriModel();

        // Cek tiap field
        $kategoriOld = $kategoriModel->find($old['id_kategori']);
        $kategoriNew = $kategoriModel->find($new['id_kategori']);

        // Ambil nama kategori lama dan baru
        $namaKategoriOld = $kategoriOld['kategori_kondisi'] ?? $old['id_kategori'];
        $namaKategoriNew = $kategoriNew['kategori_kondisi'] ?? $new['id_kategori'];

        // Loop untuk cek perubahan
        foreach ($new as $field => $value) {
            // Jika ada perubahan
            if (trim((string)($old[$field] ?? '')) !== trim((string)$value)) {
                // Khusus untuk id_kategori, tampilkan nama kategorinya
                if ($field === 'id_kategori') {
                    $changes[] = 'Kategori: ' . $namaKategoriOld . ' → ' . $namaKategoriNew;
                } 
                // Khusus untuk keterangan, tampilkan dengan format berbeda 
                else {
                    $label = ucwords(str_replace('_', ' ', $field));
                    $changes[] = $label . ': ' . ($old[$field] ?? '-') . ' → ' . $value;
                }
            }
        }

        // SIMPAN FORMAT POPUP
        $detail = implode('; ', $changes);

        // Log perubahan
        log_activity(
            'Mengedit barang: ' . $new['kode_barang'] . ' || ' . $detail,
            'barang',
            $id
        );

        // Redirect dengan pesan sukses
        return redirect()->to('/barang')->with('success', 'Data berhasil diedit.');
    }

    // Hapus data barang
    public function delete($id)
    {
        // Cek admin
        $this->mustAdmin();

        // Inisialisasi model
        $barangModel = new BarangModel();

        // Ambil data dulu sebelum dihapus
        $barang = $barangModel->find($id);
        $kodeBarang = $barang['kode_barang'] ?? $id;

        // Hapus data
        $barangModel->delete($id);

        // Log pakai kode barang
        log_activity(
            'Menghapus barang: ' . $kodeBarang,
            'barang',
            $id
        );

        // Redirect dengan pesan sukses
        return redirect()->to('/barang')->with('success', 'Data berhasil dihapus.');
    }

    // Tampilkan daftar barang yang dihapus (trash)
    public function trash()
    {
        // Cek admin
        $this->mustAdmin();

        // Inisialisasi model
        $barangModel = new BarangModel();

        // Ambil parameter filter dari query string
        $keyword      = $this->request->getGet('keyword');
        $deletedDate  = $this->request->getGet('deleted_date');

        // Query builder hanya untuk data yang dihapus
        $builder = $barangModel->onlyDeleted();

        // keyword search
        if ($keyword) {
            $builder->groupStart()
                ->like('jenis_barang', $keyword)
                ->orLike('merek_barang', $keyword)
                ->orLike('tipe_barang', $keyword)
                ->orLike('kode_barang', $keyword)
                ->groupEnd();
        }

        // filter tanggal hapus
        if ($deletedDate) {
            $builder->where('DATE(deleted_at)', $deletedDate);
        }

        // Siapkan data untuk view
        $data = [
            'barang'      => $builder->paginate(10, 'trash'),
            'pager'       => $barangModel->pager,
            'keyword'     => $keyword,
            'deletedDate' => $deletedDate
        ];

        // Tampilkan view dengan data
        return view('barang/trash', $data);
    }

    // Restore barang yang dihapus
    public function restore($id)
    {
        // Cek admin
        $this->mustAdmin();

        // Inisialisasi model
        $barangModel = new BarangModel();

        // Ambil data (termasuk yang terhapus)
        $barang = $barangModel->withDeleted()->find($id);
        $kodeBarang = $barang['kode_barang'] ?? $id;

        // Restore data
        $barangModel
            ->withDeleted()
            ->where('id_barang', $id)
            ->set('deleted_at', null)
            ->update();

        // Log pakai kode barang
        log_activity(
            'Restore barang: ' . $kodeBarang,
            'barang',
            $id
        );

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Barang berhasil direstore');
    }

    // Hapus permanen barang
    public function forceDelete($id)
    {
        // Cek admin
        $this->mustAdmin();

        // Inisialisasi model
        $barangModel = new BarangModel();

        // Ambil data dulu (termasuk soft deleted)
        $barang = $barangModel->withDeleted()->find($id);
        $kodeBarang = $barang['kode_barang'] ?? $id;

        // Hapus permanen
        $barangModel->delete($id, true);

        // Log pakai kode barang
        log_activity(
            'Hapus permanen barang: ' . $kodeBarang,
            'barang',
            $id
        );

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Barang dihapus permanen');
    }

}
