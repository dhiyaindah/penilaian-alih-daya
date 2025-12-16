<?php

namespace App\Exports;

use App\Models\TimAlihDaya;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenilaianPegawaiSheet implements
    FromArray,
    WithTitle,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithEvents
{
    protected $pegawai;
    protected $data;
    
    public function __construct(TimAlihDaya $pegawai)
    {
        $this->pegawai = $pegawai;
        $this->data = $this->getData();
    }
    
    private function getData()
    {
        $data = DB::table('penilaian')
            ->join('tim_alih_daya', 'penilaian.alih_daya_id', '=', 'tim_alih_daya.id')
            ->join('pegawai as penilai', 'penilaian.pegawai_id', '=', 'penilai.id')
            ->select(
                'penilaian.created_at',
                'penilai.nama as nama_penilai',
                'penilai.nip as nip_penilai', // Tambahkan NIP
                'penilaian.skor',
                'penilaian.catatan'
            )
            ->where('penilaian.alih_daya_id', $this->pegawai->id)
            ->orderBy('penilaian.created_at', 'desc')
            ->get();
        
        // Format ke array
        $result = [];
        $no = 1;
        
        foreach($data as $row) {
            $keterangan = match((int)$row->skor) {
                5 => 'SANGAT BAIK',
                4 => 'BAIK',
                3 => 'CUKUP',
                2 => 'KURANG',
                1 => 'SANGAT KURANG',
                default => '-'
            };
            
            $result[] = [
                $no++,
                date('d/m/Y', strtotime($row->created_at)),
                $row->nama_penilai,
                (string) $row->nip_penilai, 
                $row->skor,
                $keterangan,
                $row->catatan ?? '-'
            ];
        }
        
        return $result;
    }
        
        public function array(): array
        {
            return $this->data;
        }
        
        public function title(): string
        {
            return substr($this->pegawai->nama, 0, 20);
        }
        
        public function headings(): array
    {
        return [
            ['DATA PENILAIAN PEGAWAI ALIH DAYA'],
            [],
            ['Nama:', $this->pegawai->nama],
            ['Bidang:', ucfirst($this->pegawai->jabatan)],
            ['Total Penilaian:', count($this->data)],
            ['Rata-rata Skor:', number_format(collect($this->data)->avg(4) ?? 0, 2)], // Sekarang kolom ke-4 adalah skor
            [],
            ['NO', 'TANGGAL', 'PENILAI', 'NIP', 'SKOR', 'KETERANGAN', 'CATATAN'] // Tambah kolom NIP
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Header utama - merge sampai kolom G (karena sekarang ada 7 kolom)
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Header tabel (baris 8)
        $lastRow = count($this->data) + 8; // 8 baris header
        $sheet->getStyle('A8:G8')->getFont()->setBold(true); // Ganti F8 menjadi G8
        $sheet->getStyle('A8:G8')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');
        
        // Border untuk tabel - dari A8 sampai G
        $sheet->getStyle('A8:G' . $lastRow)->getBorders()
            ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Paksa kolom NIP (D) sebagai TEXT
        $sheet->getStyle('D9:D' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_TEXT);
        
        // Auto width untuk semua kolom
        foreach(range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Set width khusus untuk beberapa kolom
        $sheet->getColumnDimension('A')->setWidth(8);   // NO
        $sheet->getColumnDimension('B')->setWidth(12);  // TANGGAL
        $sheet->getColumnDimension('C')->setWidth(25);  // PENILAI
        $sheet->getColumnDimension('D')->setWidth(20);  // NIP
        $sheet->getColumnDimension('E')->setWidth(10);  // SKOR
        $sheet->getColumnDimension('F')->setWidth(15);  // KETERANGAN
        $sheet->getColumnDimension('G')->setWidth(40);  // CATATAN
        
        // Color coding untuk skor
        if (count($this->data) > 0) {
            $startDataRow = 9;
            
            for ($i = 0; $i < count($this->data); $i++) {
                $currentRow = $startDataRow + $i;
                $skor = $this->data[$i][4]; // Kolom ke-4 (index 4) adalah skor
                
                $fillColor = 'FFFFFFFF'; // Putih default
                
                if ($skor >= 4) {
                    $fillColor = 'FFC6EFCE'; // Hijau muda
                } elseif ($skor >= 3) {
                    $fillColor = 'FFFFEB9C'; // Kuning muda
                } elseif ($skor > 0) {
                    $fillColor = 'FFFFC7CE'; // Merah muda
                }
                
                $sheet->getStyle('A' . $currentRow . ':G' . $currentRow)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($fillColor);
                
                // Bold untuk skor
                $sheet->getStyle('E' . $currentRow)->getFont()->setBold(true);
                
                // Center alignment untuk NO, TANGGAL, SKOR, KETERANGAN
                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D' . $currentRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal('center');
            }
        }
        
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            8 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $startRow = 9; // baris data pertama
                foreach ($this->data as $index => $row) {
                    $rowNumber = $startRow + $index;

                    // Kolom D = NIP → paksa TEXT
                    $sheet->setCellValueExplicit(
                        'D' . $rowNumber,
                        $row[3], // index NIP
                        DataType::TYPE_STRING
                    );
                }
            }
        ];
    }

}