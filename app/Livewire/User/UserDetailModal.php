<?php

namespace App\Livewire\User;

use App\Livewire\Module\BaseModal;
use App\Models\User;
use Livewire\Attributes\Computed;
use Mary\Traits\Toast;

class UserDetailModal extends BaseModal
{
    use Toast;

    public $id = null;

    /*
     * normal modal title
     * @var string
     */
    protected static $title = 'locale/user.title.modal.detail';

    /*
     * load modal title
     * @var string
     */
    protected static $load_title = 'locale/user.title.modal.detail';

    /*
     * save or load permission
     * @var string|bool
     */
    protected $permission = [
        'load' => true,
        'save' => false,
    ];

    public function mount()
    {
        $this->clear();
    }

    public function render()
    {
        return view('livewire.user.user-detail-modal');
    }

    #[Computed]
    public function users()
    {
        if (is_null($this->id)) return null;
        return User::with('profile')->find($this->id);
    }

    public function load($id)
    {
        parent::load($id);
        $this->id = $id;
    }

    public function clear()
    {
        parent::clear();
        $this->id = null;
    }
}
