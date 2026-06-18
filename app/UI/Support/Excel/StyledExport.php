<?php

namespace App\UI\Support\Excel;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StyledExport implements FromQuery, ShouldAutoSize, ShouldQueue, WithColumnFormatting, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    public function __construct(
        private FromQuery&WithHeadings&WithMapping&WithColumnFormatting $domainExport
    ) {}

    /**
     * We use FromQuery instead of FromCollection to allow chunking.
     */
    public function query(): Builder
    {
        return $this->domainExport->query();
    }

    public function headings(): array
    {
        return $this->domainExport->headings();
    }

    public function map(mixed $row): array
    {
        return $this->domainExport->map($row);
    }

    /**
     * Apply Excel formatting to specific columns (Dates, Currency, Percentages).
     */
    public function columnFormats(): array
    {
        return $this->domainExport->columnFormats();
    }

    /**
     * Apply visual styling to specific rows or columns.
     */
    public function styles(Worksheet $sheet): void
    {
        $sheet->freezePane('A2');
        $sheet->getPageSetup()->setOrientation('landscape');
        $columnStyle = $sheet->getStyle('A1:'.$sheet->getHighestColumn().$sheet->getHighestRow());
        $columnStyle->getAlignment()->setWrapText(true);
        $columnStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $columnStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}
