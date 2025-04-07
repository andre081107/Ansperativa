<?php

namespace App\Controllers;

use App\Models\TrialModel;
use CodeIgniter\Controller;

class TrialController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Trial'
        ];
        return view('pages/trial', $data);
    }

    public function save()
    {
        $validation = \Config\Services::validation();

        // Validasi input: memastikan nama dan email untuk setiap pengguna diisi
        if (!$this->validate([
            'nama'  => 'required',
            'email' => 'required'
        ])) {
            // Jika validasi gagal, kembali ke form dengan pesan error
            return redirect()->to('/pages/trial')->withInput()->with('validation', $validation);
        }

        // Ambil data yang dikirimkan dari form
        $namaArray = $this->request->getVar('nama');
        $emailArray = $this->request->getVar('email');

        // Pastikan data menjadi array jika hanya ada satu pasangan yang dikirimkan
        $namaArray = is_array($namaArray) ? $namaArray : [$namaArray];
        $emailArray = is_array($emailArray) ? $emailArray : [$emailArray];


        // // Pastikan jumlah nama dan email sesuai
        // if (count($namaArray) !== count($emailArray)) {
        //     return redirect()->to('/pages/trial')->with('error', 'Data nama dan email tidak cocok!');
        // }

        // Load model untuk berinteraksi dengan database
        $userModel = new TrialModel();

        // Menyiapkan array data untuk disimpan
        $data = [];
        for ($i = 0; $i < count($namaArray); $i++) {
            $data[] = [
                'nama'  => $namaArray[$i],
                'email' => $emailArray[$i],
            ];
        }

        // Simpan data ke database
        if ($userModel->insertBatch($data)) {
            // Jika berhasil, beri pesan sukses
            return redirect()->to('/pages/trial')->with('success', 'Data berhasil disimpan!');
        } else {
            // Jika gagal menyimpan data
            return redirect()->to('/pages/trial')->with('error', 'Gagal menyimpan data!');
        }
    }
}
