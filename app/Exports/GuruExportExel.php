<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruExportExel implements FromView, WithStyles, WithEvents
{

    protected $data;

    public function __construct($json)
    {
        $this->data = $json;
    }
    public function view(): View
    {
        return view('component.exporter.GuruExportExel', [
            'json' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow(); // ambil baris terakhir yang ada datanya
        $highestColumn = $sheet->getHighestColumn(); // ambil kolom terakhir (bisa Z, AA, AB, ...)
        $range1 = 'A2:' . 'M2';
        $sheet->getStyle($range1)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // tipe solid
                'startColor' => [
                    'argb' => '4da9ff', // kode warna HEX (kuning), 6 digit
                ],
            ],
            'font' => [
                'bold' => true, 
            ],
        ]);

        $range = 'A2:' . $highestColumn . $highestRow;
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('B5:C' .   $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('0');
            }
        ];
    }
}
