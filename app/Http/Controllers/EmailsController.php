<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use YottaHQ\LaravelExtendedAuth\Events\UserEmailAddressAdded;
use YottaHQ\LaravelExtendedAuth\Models\UserEmailAddress;

class EmailsController extends Controller
{
    public function store()
    {
        request()->validate([
            'email' => [
                'email',
                'max:255',
                    Rule::unique(User::class)->ignore(request()->user()->id),
                    Rule::unique(UserEmailAddress::class),
                ],
        ]);

        $email = request()->user()->emailAddresses()->create([
            'email' => request('email'),
        ]);

        event(new UserEmailAddressAdded($email));

        return back()->with('status', 'email-added');
    }

    public function setAsPrimary()
    {
        $email = request()->user()->emailAddresses()->where('email', request('email'))->first();

        if (!$email) {
            return back()->with('error', 'email-not-found');
        }

        $email->setAsPrimary();

        return back()->with('status', 'email-set-as-primary');
    }

    public function resendVerification()
    {
        // Check if this is the primary email
        if (request()->user()->email === request('email')) {
            if (request()->user()->hasVerifiedEmail()) {
                return redirect()->intended(RouteServiceProvider::HOME);
            }

            request()->user()->sendEmailVerificationNotification();

            return back()->with('status', 'verification-link-sent');
        }

        $email = request()->user()->emailAddresses()->where('email', request('email'))->first();

        if ($email && !$email->hasVerifiedEmail()) {
            /**
             * It's important to fire the event and not use the sendEmailVerificationNotification method
             * since the listener creates a different verification URL
             */
            event(new UserEmailAddressAdded($email));
        }

        return back()->with('status', 'verification-link-sent');
    }

    public function destroy()
    {
        request()->user()->emailAddresses()->where('email', request('email'))->delete();

        return back()->with('status', 'email-address-deleted');
    }
}
