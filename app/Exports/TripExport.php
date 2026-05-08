<?php

namespace App\Exports;

use App\Models\Trip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TripExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Trip::with('vehicleRel')->orderBy('id')->get();
    }

    public function headings(): array
    {
        return [
            'Trip Code',
            'Status',
            'Prioritas',
            'Jenis Trip',
            'Region',
            'Dari',
            'Tujuan',
            'Tanggal Berangkat',
            'Estimasi Kembali',
            'Actual Berangkat',
            'Actual Kembali',
            'Driver',
            'Kendaraan',
            'No. Plat',
            'KM Awal',
            'KM Akhir',
            'Jarak Tempuh (km)',
            'BBM Digunakan (liter)',
            'Jumlah Penumpang',
            'Approver Level 1',
            'Approver Level 2',
            'Tujuan Perjalanan',
            'Catatan',
            'Dibuat Pada',
        ];
    }

    public function map($trip): array
    {
        return [
            $trip->trip_code,
            $trip->status,
            $trip->priority,
            $trip->trip_type      ?? '-',
            $trip->region         ?? '-',
            $trip->from_location,
            $trip->to_location,
            $trip->departure_datetime
                ? \Carbon\Carbon::parse($trip->departure_datetime)->format('d/m/Y H:i')
                : '-',
            $trip->arrival_datetime
                ? \Carbon\Carbon::parse($trip->arrival_datetime)->format('d/m/Y H:i')
                : '-',
            $trip->actual_departure
                ? \Carbon\Carbon::parse($trip->actual_departure)->format('d/m/Y H:i')
                : '-',
            $trip->actual_arrival
                ? \Carbon\Carbon::parse($trip->actual_arrival)->format('d/m/Y H:i')
                : '-',
            $trip->driver         ?? '-',
            $trip->vehicleRel ? $trip->vehicleRel->name        : '-',
            $trip->vehicleRel ? $trip->vehicleRel->plate_number : '-',
            $trip->km_start       ?? '-',
            $trip->km_end         ?? '-',
            $trip->km_used        ?? '-',
            $trip->fuel_used      ?? '-',
            $trip->passengers     ?? '-',
            $trip->approver1      ?? '-',
            $trip->approver2      ?? '-',
            $trip->purpose        ?? '-',
            $trip->notes          ?? '-',
            $trip->created_at ? $trip->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1A3C5E']],
            ],
        ];
    }
}