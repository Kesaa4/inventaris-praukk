<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangModel;
use App\Models\KategoriModel;

class BarangController extends BaseController
{
    public function index()
    {
        $barangModel   = new BarangModel();
        $kategoriModel = new KategoriModel();

        $keyword  = $this->request->getGet('keyword');
        $kategori = $this->request->getGet('kategori');

        $barangModel->getBarangFiltered($keyword, $kategori);

        $data = [
            'title'     => 'Data Barang',
            'barang'    => $barangModel->paginate(10, 'barang'),
            'pager'     => $barangModel->pager,
            'kategori'  => $kategoriModel->findAll(),
            'keyword'   => $keyword,
            'catFilter' => $kategori,
        ];

        return view('barang/index', $data);
    }

    private function mustAdmin()
    {
        if (session()->get('role') !== 'admin') {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }
    }

    public function create()
    {
        $this->mustAdmin();

        $kategoriModel = new KategoriModel();
        $barangModel = new BarangModel();

        return view('barang/create', [
            'kategori' => $kategoriModel->findAll(),
            'kondisi_list' => $barangModel->getEnumKondisi()
        ]);
    }

    public function store()
    {
        $this->mustAdmin();
        
        $barangModel = new BarangModel();
        $kodeBarang = $this->request->getPost('kode_barang');
        $kondisi = $this->request->getPost('kondisi');
        $status = $this->request->getPost('status');

        // Auto sync kondisi dan status
        if ($kondisi === 'Rusak Berat') {
            $status = 'tidak tersedia';
        } elseif ($status === 'tidak tersedia' && $kondisi !== 'Rusak Berat') {
            $kondisi = 'Rusak Berat';
        }

        $id = $barangModel->insert([
            'id_kategori'   => $this->request->getPost('id_kategori'),
            'merek_barang'  => $this->request->getPost('merek_barang'),
            'tipe_barang'   => $this->request->getPost('tipe_barang'),
            'kode_barang'   => $kodeBarang,
            'ram'           => $this->request->getPost('ram'),
            'rom'           => $this->request->getPost('rom'),
            'kondisi'       => $kondisi,
            'status'        => $status,
            'keterangan'    => $this->request->getPost('keterangan')
        ]);

        helper('upload');
        $file = $this->request->getFile('foto');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $validation = validateFotoBarang($file);
            
            if ($validation['valid']) {
                $fotoName = uploadFotoBarang($file, $id);
                
                if ($fotoName) {
                    $barangModel->update($id, ['foto' => $fotoName]);
                }
            }
        }

        log_activity('Menambah barang: ' . $kodeBarang, 'barang', $id);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();
        $kategoriModel = new KategoriModel();

        return view('barang/edit', [
            'barang'    => $barangModel->getBarangById($id),
            'kategori'  => $kategoriModel->findAll(),
            'kondisi_list' => $barangModel->getEnumKondisi()
        ]);
    }

    public function update($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $old = $barangModel->find($id);
        if (!$old) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $kondisi = $this->request->getPost('kondisi');
        $status = $this->request->getPost('status');

        // Auto sync kondisi dan status
        if ($kondisi === 'Rusak Berat') {
            $status = 'tidak tersedia';
        } elseif ($status === 'tidak tersedia' && $kondisi !== 'Rusak Berat') {
            $kondisi = 'Rusak Berat';
        }

        $new = [
            'id_kategori'  => $this->request->getPost('id_kategori'),
            'merek_barang' => $this->request->getPost('merek_barang'),
            'tipe_barang'  => $this->request->getPost('tipe_barang'),
            'kode_barang'  => $this->request->getPost('kode_barang'),
            'ram'          => $this->request->getPost('ram'),
            'rom'          => $this->request->getPost('rom'),
            'kondisi'      => $kondisi,
            'status'       => $status,
            'keterangan'   => trim($this->request->getPost('keterangan') ?? '')
        ];

        helper('upload');
        $file = $this->request->getFile('foto');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $validation = validateFotoBarang($file);
            
            if ($validation['valid']) {
                if (!empty($old['foto'])) {
                    deleteFotoBarang($old['foto']);
                }
                
                $fotoName = uploadFotoBarang($file, $id);
                
                if ($fotoName) {
                    $new['foto'] = $fotoName;
                }
            }
        }

        $barangModel->update($id, $new);

        $changes = [];
        $kategoriModel = new KategoriModel();

        $kategoriOld = $kategoriModel->find($old['id_kategori']);
        $kategoriNew = $kategoriModel->find($new['id_kategori']);

        $namaKategoriOld = $kategoriOld['nama_kategori'] ?? $old['id_kategori'];
        $namaKategoriNew = $kategoriNew['nama_kategori'] ?? $new['id_kategori'];

        foreach ($new as $field => $value) {
            if (trim((string)($old[$field] ?? '')) !== trim((string)$value)) {
                if ($field === 'id_kategori') {
                    $changes[] = 'Kategori: ' . $namaKategoriOld . ' → ' . $namaKategoriNew;
                } elseif ($field === 'foto') {
                    $changes[] = 'Foto: ' . ($old[$field] ? 'Diganti' : 'Ditambahkan');
                } else {
                    $label = ucwords(str_replace('_', ' ', $field));
                    $changes[] = $label . ': ' . ($old[$field] ?? '-') . ' → ' . $value;
                }
            }
        }

        $detail = implode('; ', $changes);

        log_activity('Mengedit barang: ' . $new['kode_barang'] . ' || ' . $detail, 'barang', $id);

        return redirect()->to('/barang')->with('success', 'Data berhasil diedit.');
    }

    public function delete($id)
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $barang = $barangModel->find($id);
        $kodeBarang = $barang['kode_barang'] ?? $id;

        $barangModel->delete($id);

        log_activity('Menghapus barang: ' . $kodeBarang, 'barang', $id);

        return redirect()->to('/barang')->with('success', 'Data berhasil dihapus.');
    }

    public function trash()
    {
        $this->mustAdmin();

        $barangModel = new BarangModel();

        $keyword      = $this->request->getGet('keyword');
        $kondisi      = $this->request->getGet('kondisi');
        $status       = $this->request->getGet('status');
        $deletedDate  = $this->request->getGet('deleted_date');

        $builder = $barangModel->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left')
            ->onlyDeleted();

        if ($keyword) {
            $builder->groupStart()
                ->like('kategori.nama_kategori', $keyword)
                ->orLike('merek_barang', $keyword)
                ->orLike('tipe_barang', $keyword)
                ->orLike('kode_barang', $keyword)
                ->groupEnd();
        }

        if ($kondisi) {
            $builder->where('barang.kondisi', $kondisi);
        }

        if ($status) {
            $builder->where('barang.status', $status);
        }

        if ($deletedDate) {
            $builder->where('DATE(barang.deleted_at)', $deletedDate);
        }

        $data = [
            'barang'      => $builder->paginate(10, 'trash'),
            'pager'       => $barangModel->pager,
            'keyword'     => $keyword,
            'kondisi'     => $kondisi,
            'status'      => $status,
            'deletedDate' => $deletedDate
        ];

        return view('barang/trash', $data);
    }

    public function history($id)
    {
        $barangModel = new BarangModel();

        $barang = $barangModel->getBarangById($id);
        
        if (!$barang) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $riwayat = $db->table('pinjam')
            ->select('pinjam.*, user.email, userprofile.nama')
            ->join('user', 'user.id_user = pinjam.id_user', 'left')
            ->join('userprofile', 'userprofile.id_user = user.id_user', 'left')
            ->where('pinjam.id_barang', $id)
            ->where('pinjam.deleted_at', null)
            ->orderBy('pinjam.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $totalPinjam = count($riwayat);
        $totalDikembalikan = count(array_filter($riwayat, fn($r) => $r['status'] === 'dikembalikan'));

        $data = [
            'barang' => $barang,
            'riwayat' => $riwayat,
            'totalPinjam' => $totalPinjam,
            'totalDikembalikan' => $totalDikembalikan
        ];

        return view('barang/history', $data);
    }

    public function exportExcel()
    {
        $barangModel = new BarangModel();

        $keyword = $this->request->getGet('keyword');
        $kategori = $this->request->getGet('kategori');

        $barang = $barangModel->getBarangFiltered($keyword, $kategori)->findAll();

        helper('excel');

        $headers = [
            'No',
            'Kategori',
            'Merek',
            'Tipe',
            'Kode Barang',
            'RAM',
            'ROM',
            'Kondisi',
            'Status',
            'Keterangan'
        ];

        $data = [];
        $no = 1;
        foreach ($barang as $b) {
            $data[] = [
                $no++,
                $b['nama_kategori'],
                $b['merek_barang'],
                $b['tipe_barang'],
                $b['kode_barang'],
                $b['ram'],
                $b['rom'],
                $b['kondisi'],
                ucfirst($b['status']),
                $b['keterangan'] ?? '-'
            ];
        }

        $filename = 'Data_Barang_' . date('Y-m-d_His') . '.xlsx';
        $title = 'DATA BARANG INVENTARIS';

        log_activity('Export data barang ke Excel', 'barang', 0);

        exportToExcel($data, $headers, $filename, $title);
    }

    // Hapus foto barang
    public function deleteFoto($id)
    {
        $this->mustAdmin();

        helper('upload');
        $barangModel = new BarangModel();

        $barang = $barangModel->find($id);

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        if (empty($barang['foto'])) {
            return redirect()->back()->with('error', 'Barang tidak memiliki foto');
        }

        if (deleteFotoBarang($barang['foto'])) {
            $barangModel->update($id, ['foto' => null]);

            log_activity('Menghapus foto barang: ' . $barang['kode_barang'], 'barang', $id);

            return redirect()->back()->with('success', 'Foto berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus foto');
    }
}
