<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\Auth\UserDestination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->to(UserDestination::for(
                $request->user(),
                UserDestination::isSafePublicRedirect($request->query('redirect'))
                    ? $request->query('redirect')
                    : UserDestination::publicHome(),
            ));
        }

        return view('auth.login', [
            'redirectTo' => UserDestination::isSafePublicRedirect($request->query('redirect'))
                ? $request->query('redirect')
                : UserDestination::publicHome(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');
        $redirectTo = UserDestination::isSafePublicRedirect($request->string('redirect_to')->toString())
            ? $request->string('redirect_to')->toString()
            : UserDestination::publicHome();

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => __('Las credenciales proporcionadas no son correctas.'),
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->to(UserDestination::for($request->user(), $redirectTo));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirectTo = UserDestination::isSafePublicRedirect($request->string('redirect_to')->toString())
            ? $request->string('redirect_to')->toString()
            : UserDestination::publicHome();

        return redirect()->to($redirectTo);
    }
}
