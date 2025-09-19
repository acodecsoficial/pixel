<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\Config;
use App\Models\User;
use App\Traits\SendMail;
use App\Traits\SendWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    use SendMail, SendWebhook;

    protected $configs;

    public function __construct()
    {
        $this->configs = Config::first();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $credentials = $request->only(['email', 'password']);

        if (!$this->checkCaptchaIsValid()) {
            return response()->json([
                'message' => __("back.captcha-not-valid"),
                'success' => false,
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user && !is_null($user->deleted_at)) {
            return response()->json([
                'error' => __("back.account-not-found")
            ], 400);
        }

        if (!Auth::attempt($credentials, true)) {
            return response()->json([
                'message' => __('auth.failed'),
            ], 401);
        }

        $token = $this->createToken();

        $this->loginHook(auth()->user());

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'phone'         => 'required|unique:users,phone',
            'ddi'           => 'required',
        ]);

        if (!$this->checkCaptchaIsValid()) {
            return response()->json([
                'message' => __("back.captcha-not-valid"),
                'success' => false,
            ], 400);
        }

        $user = $this->createUser([
            'affiliation_code' => $request->input('affiliation_code'),
            'email'            => $request->input('email'),
            'ddi'              => $request->input('ddi'),
            'name'             => $request->input('name'),
            'password'         => $request->input('password'),
            'phone'            => $request->input('phone'),
            'address'            => $request->input('address'),
            'birth_date'            => $request->input('birth_date'),
        ]);

        Auth::login($user, true);

        $token = $this->createToken();

        return $this->respondWithToken($token);
    }

    public function loginWithGoogle()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = $this->createUser([
            'email' => $googleUser->email,
            'name'  => $googleUser->name,
        ]);

        Auth::login($user, true);

        $token = $this->createToken();

        return "<script>
            localStorage.setItem('token', '$token->plainTextToken');
            location.href = '/';
        </script>";
    }

    protected function createUser(array $data): User
    {
        $affiliation_code = $data['affiliation_code'] ?? '';
        $inviter          = null;

        if (isset($affiliation_code) && !empty($affiliation_code)) {
            $userInviter = User::where('code', $affiliation_code)->first();
            if (isset($userInviter)) {
                $inviter = $userInviter->email;
            }
        }

        $user = User::firstOrCreate([
            'email' => $data['email'],
        ], [
            'email'        => $data['email'],
            'name'         => $data['name'] ?? $data['email'],
            'cpf'          => null,
            'phone'        => $data['phone'] ?? '',
            'ddi'          => $data['ddi'] ?? '',
            'password'     => array_key_exists('password', $data)
                ? Hash::make($data['password'])
                : null,
            'code'         => $this->generateAffiliationCode(),
            'username'     => $this->generateRandomUsername(),
            'birth_date'   => $data['birth_date'] ?? '',
            'ip_address'   => request()->ip(),
            'value_cpa'    => $this->configs->cpa_value,                         // CPA ( $ )
            'referPercent' => $this->configs->ngr_percent,                      // REV ( % ),
            'subPercent'   => $this->configs->sub_percent,
            'baseline'     => $this->configs->cpa_min,
            'referFake'    => $this->configs->rev_fake,
            'cpa_chance'   => $this->configs->cpa_chance,
            'inviter'      => $inviter,
            'address'      => $data['address'] ?? ''
        ]);

        if ($user->wasRecentlyCreated) {
            $this->sendRegisterMail($user);
            $this->registerHook($user);
        }

        return $user;
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function sendResetPasswordMail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $url = URL::temporarySignedRoute('password.reset', now()->addMinutes(30), ['email' => $user->email]);

        Mail::to($user)->sendNow(new ResetPasswordMail($url, $user));

        return response()->json([
            'message' => __("back.resetpass-email-send"),
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'        => ['required', 'email'],
            'new_password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', __("back.user.changepass"));
    }


    protected function checkCaptchaIsValid()
    {
        if (!$this->configs->cf_key) {
            return true;
        }

        request()->validate([
            'captcha_token' => 'required'
        ]);

        $response = Http::post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => $this->configs->cf_secret,
            'response' => request()->input('captcha_token'),
            'remoteip' => request()->ip(),
        ]);

        return $response->successful() && $response->json('success');
    }

    protected function generateAffiliationCode()
    {
        do {
            $code = str()->random(7);
        } while (User::where('code', $code)->exists());

        return $code;
    }

    protected function generateRandomUsername()
    {
        do {
            $username = str()->random(12);
        } while (User::where('username', $username)->exists());

        return $username;
    }

    protected function createToken()
    {
        return auth()->user()->createToken(
            'authToken',
            abilities: ['*'],
            expiresAt: now()->addDays(1),
        );
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'message'      => "Token successfully created",
            'access_token' => $token->plainTextToken,
            'expires_at'   => $token->accessToken->expires_at,
        ], 200);
    }
}
