<?php

namespace App\Imports;

use App\Models\TimAlihDaya;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TimAlihDayaImport implements ToModel, WithHeadingRow
{
   public function model(array $row)
    {
        // Hanya ambil kolom string associative
        $row = array_filter($row, function($key) {
            return !is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        // Abaikan row yang semua kolom penting kosong
        if (empty($row['nama']) && empty($row['jabatan'])) {
            return null; 
        }

        return new TimAlihDaya([
            'nama'             => $row['nama'],
            'jabatan'          => $row['jabatan'],
        ]);
    }
}
