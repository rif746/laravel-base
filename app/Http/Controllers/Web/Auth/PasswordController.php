<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\SuccessResource;
use App\Models\Identity\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        User::where('id', $request->user()->id)->update([
            'password' => bcrypt($validated['password']),
        ]);

        if ($request->wantsJson()) {
            return new SuccessResource(__('ui.crud.success.updated', ['resource' => __('domains/identity.fields.user.password')]));
        }

        return back()->with('status', 'password-updated');
    }
}
