<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Contracts\View\View;

class SheetAbsenExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function view(): View
    {
        $dates = CarbonPeriod::create($this->from, $this->to)
            ->map(fn($d) => $d->toDateString())
            ->toArray();

        $users = User::whereIn('role', ['user','security','cleaning','kantoran'])
            ->orderBy('name')
            ->get();

        $attendance = Attendance::whereBetween('date', [$this->from, $this->to])
            ->get()
            ->groupBy('user_id')
            ->map(fn($items) => $items->keyBy('date')
                ->map(fn($i) => $i->status ?? '-')
                ->toArray()
            );

        return view('exports.sheet_absen', compact('users','dates','attendance'));
    }

    public function styles(Worksheet $sheet)
    {
        $users    = $this->view()->getData()['users'];
        // Header = 2 baris, data mulai baris 3
        $dataStart = 3;
        $lastRow   = $dataStart - 1 + count($users);

        // Header baris 1-2 saja
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Border seluruh data
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);

        // Format No HP (kolom C) agar tidak jadi scientific notation
        for ($row = $dataStart; $row <= $lastRow; $row++) {
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('@');
        }

        // Tinggi baris header
        for ($i = 1; $i <= 2; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
    }
}