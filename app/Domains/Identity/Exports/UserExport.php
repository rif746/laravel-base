<?php

namespace App\Domains\Identity\Exports;

use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserExport implements FromQuery, WithColumnFormatting, WithHeadings, WithMapping
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
}
