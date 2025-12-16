<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class RekapSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles
{
    public function title(): string
    {
        return 'REKAP SEMUA PEGAWAI';
    }
    
    public function collection()
    {
        return DB::table('penilaian')
            ->join('tim_alih_daya', 'penilaian.alih_daya_id', '=', 'tim_alih_daya.id')
            ->select(
                'tim_alih_daya.id',
                'tim_alih_daya.nama',
                'tim_alih_daya.jabatan',
                DB::raw('COUNT(penilaian.id) as total_penilaian'),
                DB::raw('ROUND(AVG(penilaian.skor), 2) as rata_rata'),
                DB::raw('MAX(penilaian.skor) as skor_tertinggi'),
                DB::raw('MIN(penilaian.skor) as skor_terendah'),
                DB::raw('MAX(penilaian.created_at) as penilaian_terakhir')
            )
            ->groupBy('tim_alih_daya.id', 'tim_alih_daya.nama', 'tim_alih_daya.jabatan')
            ->orderBy('tim_alih_daya.jabatan')
            ->orderBy('tim_alih_daya.nama')
            ->get();
    }
    
    public function headings(): array
    {
        return [
            ['REKAP PENILAIAN SEMUA PEGAWAI ALIH DAYA'],
            [''],
            ['NO', 'NAMA PEGAWAI', 'BIDANG', 'TOTAL PENILAIAN', 'RATA-RATA', 'SKOR TERTINGGI', 'SKOR TERENDAH', 'PENILAIAN TERAKHIR']
        ];
    }
    
    public function map($row): array
    {
        return [
            '', // NO akan diisi di styles
            $row->nama,
            strtoupper($row->jabatan),
            $row->total_penilaian,
            $row->rata_rata,
            $row->skor_tertinggi,
            $row->skor_terendah,
            $row->penilaian_terakhir ? date('d/m/Y', strtotime($row->penilaian_terakhir)) : '-'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Merge cell untuk judul
        $sheet->mergeCells('A1:H1');
        
        // Styling judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => 'center']
        ]);
        
        // Styling header tabel
        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F81BD']
            ],
            'font' => ['color' => ['argb' => 'FFFFFFFF']],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);
        
        // Auto width
        foreach(range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Numbering rows dan styling data
        $rowNumber = 4;
        $no = 1;
        foreach($this->collection() as $row) {
            $sheet->setCellValue('A' . $rowNumber, $no);
            
            // Warna baris berdasarkan rata-rata
            $rata = $row->rata_rata;
            $fillColor = 'FFFFFFFF'; // putih default
            
            if ($rata >= 4) {
                $fillColor = 'FFC6EFCE'; // hijau muda
            } elseif ($rata >= 3) {
                $fillColor = 'FFFFEB9C'; // kuning muda
            } else {
                $fillColor = 'FFFFC7CE'; // merah muda
            }
            
            $sheet->getStyle('A' . $rowNumber . ':H' . $rowNumber)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => $fillColor]
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]);
            
            $rowNumber++;
            $no++;
        }
        
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            3 => ['font' => ['bold' => true]],
        ];
    }
}