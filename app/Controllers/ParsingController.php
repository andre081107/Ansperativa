<?php

namespace App\Controllers;

use App\Models\FileModel;
use App\Models\ParsingModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ParsingController extends BaseController
{
    protected $parsingModel;
    protected $fileModel;

    // Constructor untuk menginisialisasi model yang digunakan pada controller ini
    public function __construct()
    {
        $this->parsingModel = new ParsingModel();
        $this->fileModel = new FileModel();
    }

    // Method untuk menampilkan halaman utama Parsing
    public function index()
    {
        $session = session();
        $succesMessage = $session->getFlashdata('pesan');

        $data = [
            'title' => 'Parsing',
            'pesan' => $succesMessage
        ];
        return view('pages/parsing', $data);
    }

    // Method untuk menyimpan file Excel yang di-upload oleh user
    public function save()
    {
        // Memeriksa validasi file upload
        if (!$this->validate([
            'excel_file' => [
                'rules' => 'uploaded[excel_file]|max_size[excel_file,2048]|ext_in[excel_file,xlsx,xls]',
                'errors' => [
                    'uploaded' => 'Pilih File Terlebih Dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'ext_in' => 'Yang anda pilih bukan file excel',
                ]
            ]
        ])) {
            // Jika validasi gagal, kembalikan view dengan pesan error
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->to('/pages/parsing');
        } else {
            // Jika validasi berhasil, simpan file dan redirect dengan pesan sukses
            $fileExcel = $this->request->getFile('excel_file');
            $originalFileName = $fileExcel->getName();
            $extension = $fileExcel->getClientExtension();
            $timestamp = time();
            $hashedFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $extension;
            $fileExcel->move('uploads', $hashedFileName);

            // Simpan nama file ke session
            session()->set('excel_file', $hashedFileName);

            // Redirect ke halaman load
            return redirect()->to('/pages/load')->with('pesan', 'Data berhasil ditambahkan');
        }
    }

    // Method untuk menampilkan halaman load
    public function load()
    {
        $fileName = session()->get('excel_file');
        $filePath = 'uploads/' . $fileName;

        if (!file_exists($filePath)) {
            return view('/pages/load', [
                'title' => 'Load Page',
                'error' => 'File tidak ditemukan.'
            ]);
        }

        // Baca file Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Pemeriksaan kolom kosong dalam setiap baris
        foreach ($data as $index => $row) {
            foreach ($row as $cellIndex => $cell) {
                $trimmedCell = trim($cell);
                if ($trimmedCell === '' || $cell === null) {
                    return view('/pages/load', [
                        'title' => 'Load Page',
                        'file_name' => $fileName,
                        'error' => 'Gagal Membaca File, Terdapat kolom kosong pada baris ' . ($index + 1) . ', kolom ' . ($cellIndex + 1) . '.'
                    ]);
                }
            }
        }

        // Cek apakah ada kolom "Prioritas"
        $header = $data[0];
        $prioritasIndex = array_search('prioritas', $header);

        if ($prioritasIndex !== false) {
            foreach ($data as $index => $row) {
                if ($index == 0) continue;

                $prioritasValue = $row[$prioritasIndex];
                if (!is_numeric($prioritasValue)) {
                    return view('/pages/load', [
                        'title' => 'Load Page',
                        'file_name' => $fileName,
                        'error' => 'Kolom "prioritas" pada baris ' . ($index + 1) . ' harus berupa angka.'
                    ]);
                }

                $prioritasString = (string)$prioritasValue;
                if ($prioritasString === '0') {
                    continue;
                }

                if (preg_match('/^0+$/', $prioritasString)) {
                    return view('/pages/load', [
                        'title' => 'Load Page',
                        'file_name' => $fileName,
                        'error' => 'Di baris ' . ($index + 1) . ', kolom "prioritas" hanya boleh ada satu angka 0.'
                    ]);
                }
            }
        }

        // Kirim data ke API Node.js
        $this->sendDataToNodeJsApi($data, $fileName);

        // Kirim data ke view jika tidak ada kolom kosong dan validasi berhasil
        return view('/pages/load', [
            'title' => 'Load Page',
            'file_name' => $fileName,
            'success' => 'Data berhasil dimuat dan dikirim ke API Node.js.'
        ]);
    }

    // Method untuk mengirim data ke API Node.js
    private function sendDataToNodeJsApi($data, $fileName)
    {
        $url = 'http://localhost:3200/receiveData';  // Ganti dengan URL API Node.js Anda

        // Persiapkan data yang akan dikirim
        $dataToSend = [];

        foreach ($data as $index => $row) {
            if ($index == 0) continue;  // Lewati baris header

            // Memastikan data yang dikirim sesuai dengan format yang diinginkan oleh API
            $dataToSend[] = [
                'kode_modul' => $row[0],
                'kode_produk' => $row[1],
                'perintah' => $row[2],
                'aktif' => $row[3],
                'prioritas' => $row[4]  // Kolom prioritas
            ];
        }

        // Menggunakan cURL untuk mengirim data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataToSend));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        // Eksekusi cURL dan simpan respons
        $response = curl_exec($ch);

        // Debugging untuk mengecek jika ada error
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            log_message('error', "cURL Error: " . $error_msg);
        } else {
            log_message('info', "Response from Node.js: " . $response);
        }

        curl_close($ch);
    }

    // Method to handle the deletion of data by calling the Node.js API
    public function delete($kode_modul, $kode_produk)
    {
        // Call the Node.js API to delete the data
        $this->deleteDataFromNodeJsApi($kode_modul, $kode_produk);

        // Set flash message for success after the deletion request
        session()->setFlashdata('pesan', 'Data berhasil dihapus di Node.js');

        return redirect()->to('/pages/parsing');
    }

    // Method to send DELETE request to Node.js API
    private function deleteDataFromNodeJsApi($kode_modul, $kode_produk)
    {
        $url = 'http://localhost:3200/deleteData/' . $kode_modul . '/' . $kode_produk; // Node.js DELETE endpoint

        // Initialize cURL session
        $ch = curl_init();

        // Set the necessary cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        // Execute the cURL session and get the response
        $response = curl_exec($ch);

        // Check if there were any cURL errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            log_message('error', "cURL Error: " . $error_msg);
            session()->setFlashdata('errors', "Failed to delete data: " . $error_msg);
        } else {
            log_message('info', "Response from Node.js: " . $response);
            if ($response === "Data deleted successfully") {
                // Success message logged if deletion was successful
                session()->setFlashdata('pesan', "Data successfully deleted");
            } else {
                session()->setFlashdata('errors', "Failed to delete data: " . $response);
            }
        }

        // Close the cURL session
        curl_close($ch);
    }
}
