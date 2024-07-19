<?php

namespace App\Livewire\Forms\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\Rule;
use Livewire\Form;
use Illuminate\Validation\Rules;

class RegisterForm extends Form
{
    #[Rule(['required', 'string', 'email'])]
    public $email;

    #[Rule(['required', 'string', 'max:255', 'unique:' . User::class . ',name'])]
    public $name;

    #[Rule(['required', 'string'])]
    public $gender;

    #[Rule(['required', 'string'])]
    public $address;

    #[Rule(['required', 'confirmed'])]
    public $password;
    public $password_confirmation;

    protected function rules()
    {
        return [
            'password' => [Rules\Password::default()]
        ];
    }

    public function post()
    {
        $this->validate();
        $user = User::create([
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ])->profile()->create([
            'name' => $this->name,
            'gender' => $this->gender,
            'address' => $this->address,
        ]);

        event(new Registered($user));

        auth()->login($user);

        return redirect('/landing');
    }
}
