<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class RekapSemuaExport implements FromView, WithStyles, ShouldAutoSize
{
    protected $from;
    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function view(): View
    {
        $minAbs   = DB::table('attendances')->min('date');
        $maxAbs   = DB::table('attendances')->max('date');
        $minPanen = DB::table('catatan_panen')->min('tanggal');
        $maxPanen = DB::table('catatan_panen')->max('tanggal');

        if (!$this->from || !$this->to) {
            $this->from = min($minAbs, $minPanen);
            $this->to   = max($maxAbs, $maxPanen);
        }

        $from = Carbon::parse($this->from);
        $to   = Carbon::parse($this->to);

        $dates = [];
        $period = new \DatePeriod($from, new \DateInterval('P1D'), $to->copy()->addDay());
        foreach ($period as $d) {
            $dates[] = $d->format('Y-m-d');
        }

        $users = DB::table('users')
            ->orderBy('name')
            ->get();

        $absensi = DB::table('attendances')
            ->whereBetween('date', [$this->from, $this->to])
            ->select('user_id', 'date', 'status')
            ->get()
            ->groupBy(function ($item) {
                return $item->user_id . '-' . $item->date;
            });

        $panen = DB::table('catatan_panen')
            ->whereBetween('tanggal', [$this->from, $this->to])
            ->select('id_pegawai', 'tanggal', 'berat_kg')
            ->get()
            ->groupBy(function ($item) {
                return $item->id_pegawai . '-' . $item->tanggal;
            });

        return view('exports.sheet_aggregate', [
            'dates' => $dates,
            'users' => $users,
            'absensi' => $absensi,
            'panen' => $panen,
            'from' => $this->from,
            'to' => $this->to
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $users     = $this->view()->getData()['users'];
        // Header = 2 baris, data mulai baris 3
        $dataStart = 3;
        $lastRow   = $dataStart - 1 + count($users);

        // Header baris 1-2 saja
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
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

        // Baris total terakhir (warna abu-abu)
        $sheet->getStyle('A' . $lastRow . ':' . $sheet->getHighestColumn() . $lastRow)->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']],
            'font' => ['bold' => true],
        ]);

        // Tinggi baris header
        for ($i = 1; $i <= 2; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
    }
}