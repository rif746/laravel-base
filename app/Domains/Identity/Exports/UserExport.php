<?php

namespace App\Domains\Identity\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Domains\Identity\Models\User;

class UserExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    /**
     * We use FromQuery instead of FromCollection to allow chunking.
     */
    public function query(): Builder
    {
        return User::query()->with('profile');
    }

    public function headings(): array
    {
        return [
            'No',
            'Name',
            'Email',
            'Gender',
            'Date Of Birth',
            'Phone',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map(mixed $row): array
    {
        $gender = $row->profile?->gender?->label() ?: '-';
        $dateOfBirth = $row->profile?->date_of_birth ?: '-';
        $phone = $row->profile?->phone_number ?: '-';
        return [
            '=ROW()-1',
            $row->name,
            $row->email,
            $gender,
            $dateOfBirth,
            $phone,
        ];
    }

    /**
     * Apply Excel formatting to specific columns (Dates, Currency, Percentages).
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * Apply visual styling to specific rows or columns.
     */
    public function styles(Worksheet $sheet): void
    {
        $sheet->freezePane('A2');
        $sheet->getPageSetup()->setOrientation('landscape');
        $columnStyle = $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow());
        $columnStyle->getAlignment()->setWrapText(true);
        $columnStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $columnStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}
