<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $input = trim((string) $this->input('email'));
        $password = (string) $this->input('password');

        // Bersihkan NIP angka (jika ada spasi / tanda minus)
        $cleanDigits = preg_replace('/[^0-9]/', '', $input);

        // Cari user berdasarkan multi-field
        $user = \App\Models\User::query()
            ->where(function ($query) use ($input, $cleanDigits) {
                // 1. Cek Email tepat
                $query->where('email', $input)
                      // Fallback typo domain jika ada
                      ->orWhere('email', str_replace('@smpn3lakbok.sch.id', '@smpn3lakbok.sch', $input))
                      ->orWhere('email', str_replace('@smpn3lakbok.sch', '@smpn3lakbok.sch.id', $input));

                // 2. Cek NIP
                if (!empty($input) && $input !== '-') {
                    $query->orWhere('nip', $input);
                }
                if (!empty($cleanDigits) && strlen($cleanDigits) >= 6) {
                    $query->orWhereRaw("REPLACE(REPLACE(nip, ' ', ''), '-', '') = ?", [$cleanDigits]);
                }

                // 3. Cek Username / Prefix Email atau Nama (misal guru ketik: 'riyanti', 'titi.setiawati', atau 'Riyanti')
                if (!str_contains($input, '@') && strlen($input) >= 2) {
                    $query->orWhere('email', $input . '@smpn3lakbok.sch.id')
                          ->orWhere('email', 'like', $input . '@%')
                          ->orWhere('name', $input)
                          ->orWhere('name', 'like', $input . '%');
                }
            })
            ->first();

        $authenticated = false;
        if ($user) {
            if (\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                $authenticated = true;
            } else {
                // Kompatibilitas cerdas kata sandi bawaan sekolah
                $commonDefaults = ['password123', 'guru123', '20211583', '12345678', 'password'];
                $cleanInputPass = preg_replace('/[^0-9]/', '', $password);
                $userNipClean = !empty($user->nip) ? preg_replace('/[^0-9]/', '', $user->nip) : null;
                $isNipPassword = $userNipClean && strlen($userNipClean) >= 8 && $cleanInputPass === $userNipClean;

                // Cek apakah akun ini di database masih berstatus sandi awal / bawaan
                $isUnderDefault = false;
                foreach (['password123', 'guru123', '20211583', 'password'] as $defaultCandidate) {
                    if (\Illuminate\Support\Facades\Hash::check($defaultCandidate, $user->password)) {
                        $isUnderDefault = true;
                        break;
                    }
                }

                if ($isUnderDefault && (in_array($password, $commonDefaults) || $isNipPassword)) {
                    $user->password = \Illuminate\Support\Facades\Hash::make($password);
                    $user->save();
                    $authenticated = true;
                }
            }
        }

        if ($authenticated && $user) {
            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Fallback default Laravel attempt
        if (Auth::attempt(['email' => $input, 'password' => $password], $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
