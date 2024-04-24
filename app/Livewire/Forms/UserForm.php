<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Form;

class UserForm extends Form
{
    #[Locked]
    public $id;

    #[Rule('required', as: 'Email')]
    #[Rule('email', as: 'Email')]
    #[Rule('string', as: 'Email')]
    public $email;

    #[Rule('required', as: 'Name')]
    #[Rule('min:5', as: 'Name')]
    public $name;

    protected function rules()
    {
        return [
            'name' => [(new Rules\Unique(User::class, 'name'))->ignore($this->id, 'id')],
            'email' => [(new Rules\Unique(User::class, 'email'))->ignore($this->id, 'id')],
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

        if (!isset($user->id)) {
            return $user->save();
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        return $user->update();
    }
}
