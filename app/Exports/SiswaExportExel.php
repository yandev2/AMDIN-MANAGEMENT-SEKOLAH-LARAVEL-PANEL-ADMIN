<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SiswaExportExel implements FromView, WithStyles, WithEvents
{

    protected $data;

    public function __construct($json)
    {
        $this->data = $json;
    }
    public function view(): View
    {
        return view('component.exporter.SiswaExportExel', [
            'json' => $this->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow(); // ambil baris terakhir yang ada datanya
        $highestColumn = $sheet->getHighestColumn(); // ambil kolom terakhir (bisa Z, AA, AB, ...)
        $range = 'A3:' . $highestColumn . $highestRow;
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('000000');
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('B5:B' .   $lastRow)
                    ->getNumberFormat()
                    ->setFormatCode('0');
            }
        ];
    }
}
