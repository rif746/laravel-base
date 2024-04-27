<?php

namespace App\Livewire\User;

use App\Exports\UserExport;
use App\Livewire\Module\BaseTable;
use App\Livewire\Module\Trait\Notification;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class UserTable extends BaseTable
{
    use Notification;

    #[Locked]
    public $title = "User Table";

    #[Url('q', history: true)]
    public $search = "";

    protected array $modals = [
        'create' => 'create-user-form-modal',
        'edit' => 'update-user-form-modal',
    ];

    protected array $permissions = [
        'create' => 'user create',
        'edit' => 'user edit',
        'delete' => 'user delete',
    ];

    protected array $export = [
        'pdf' => 'exportPDF',
        'xlsx' => 'exportXLSX',
    ];

    public function render()
    {
        return view("livewire.user.user-table", $this->getData());
    }

    #[Computed]
    public function users()
    {
        return User::search($this->search)
            ->orderBy($this->sort_by, $this->sort_direction)
            ->paginate($this->perPage);
    }

    public function cols()
    {
        return [
            [
                "label" => "Name",
                "query" => "name",
                "sort" => true,
            ],
            [
                "label" => "Email",
                "query" => "email",
                "sort" => false,
            ],
        ];
    }

    public function delete($id)
    {
        parent::delete($id);
        User::destroy($id);
    }

    public function exportXLSX()
    {
        return FacadesExcel::download(
            new UserExport(),
            "download.xlsx",
            Excel::XLSX
        );
    }

    public function exportPDF()
    {
        return FacadesExcel::download(
            new UserExport(),
            "download.pdf",
            Excel::DOMPDF
        );
    }
}
