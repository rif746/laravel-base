<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserExport implements
    FromCollection,
    WithEvents,
    WithColumnFormatting,
    WithMapping,
    ShouldAutoSize,
    WithProperties,
    WithHeadings,
    WithTitle,
    WithCustomStartCell
{
    public function title(): string
    {
        return 'User Data';
    }

    public function properties(): array
    {
        return [
            'title'          => 'User Data',
            'description'    => 'Exported user data',
            'subject'        => 'Data',
            'keywords'       => 'user data, Account Data',
            'category'       => 'Account',
            'creator'        => env('EXCEL_CREATOR', 'Syarif Ubaidillah'),
            'lastModifiedBy' => env('EXCEL_CREATOR', 'Syarif Ubaidillah'),
            'manager'        => env('EXCEL_CREATOR', 'Syarif Ubaidillah'),
            'company'        => env('EXCEL_COMPANY', 'Rif Studio'),
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $workSheet = $event->sheet->getDelegate();
                $workSheet->freezePane("A2");
            }
        ];
    }

    public function collection(): Collection
    {
        return User::all();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
        ];
    }

    public function columnFormats(): array
    {
        return [
            "A" => NumberFormat::FORMAT_TEXT,
            "B" => NumberFormat::FORMAT_TEXT,
            "C" => NumberFormat::FORMAT_TEXT,
            "D" => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Password',
        ];
    }
}
