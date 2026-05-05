<?php

namespace App\Http\Controllers\Web\Account;

use App\Attributes\Seo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Account\ProfileUpdateRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\Web\Account\ProfileResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    #[Seo(
        title: 'domains/account.seo.profile.title',
        description: 'domains/account.seo.profile.description',
        keywords: 'domains/account.seo.profile.keywords'
    )]
    public function index(): View|JsonResource
    {
        if (request()->wantsJson()) {
            $user = request()->user()->load('profile');

            return new ProfileResource($user);
        }

        return view('pages.account.profile.index');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): JsonResource
    {
        $user = $request->user();
        $validated = collect($request->validated());
        $user->fill($validated->only(['name', 'email'])->toArray());
        $user->profile()->updateOrCreate([], $validated->except(['name', 'email'])->toArray());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return new SuccessResource(__('ui.crud.success.updated', ['resource' => __('resources.profile')]));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
