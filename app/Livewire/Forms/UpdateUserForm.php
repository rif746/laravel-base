<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateUserForm extends Form
{
    #[Locked]
    public $id;

    #[Validate('required', as: 'Email')]
    #[Validate('email', as: 'Email')]
    #[Validate('string', as: 'Email')]
    public $email;

    #[Validate('required', as: 'Name')]
    #[Validate('min:5', as: 'Name')]
    public $name;

    protected function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email,id,' . $this->id],
            'name' => ['required', 'min:5', 'unique:users,name,id,' . $this->id],
        ];
    }

    public function load($id)
    {
        $user = User::find($id);
        $this->id = $user->id;
        $this->email = $user->email;
        $this->name = $user->name;
    }

    public function clear()
    {
        $this->id = 0;
        $this->email = "";
        $this->name = "";
    }

    public function post()
    {
        $this->validate();
        $user = User::findOrNew($this->id);
        $update['name'] = $this->name;
        $update['email'] = $this->email;
        $user->fill($update);
        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        return $user->update();
    }
}
