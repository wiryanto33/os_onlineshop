<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Register extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $phone = '';
    public $address = '';
    public $foto_profile;
    public $foto_ktp;
    public $facebook = '';
    public $instagram = '';
    public $tiktok = '';
    public $role = '';
    public $showPassword = false;
    public $passwordConfirmationTouched = false;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'password_confirmation' => 'required',
        'phone' => 'nullable|numeric',
        'address' => 'nullable',
        'foto_profile' => 'nullable|image|max:1024', // Max 1MB
        'foto_ktp' => 'nullable|image|max:1024', // Max 1MB
        'facebook' => 'nullable|url',
        'instagram' => 'nullable|url',
        'tiktok' => 'nullable|url',
        'role' => 'nullable|in:distributor,agent,stokist'
    ];

    protected $messages = [
        'name.required' => 'Nama wajib diisi',
        'name.min' => 'Nama minimal 3 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password_confirmation.required' => 'Konfirmasi password wajib diisi',
        'phone.numeric' => 'Nomor telepon harus berupa angka',
        'address.required' => 'Alamat wajib di isi',
        'foto_profile.image' => 'Foto profil harus berupa gambar',
        'foto_profile.max' => 'Ukuran foto profil maksimal 1MB',
        'foto_ktp.image' => 'Foto KTP harus berupa gambar',
        'foto_ktp.max' => 'Ukuran foto KTP maksimal 1MB',
        'facebook.url' => 'URL Facebook tidak valid',
        'instagram.url' => 'URL Instagram tidak valid',
        'tiktok.url' => 'URL TikTok tidak valid',
        'role.in' => 'Role harus salah satu dari distributor, agent, atau stokist'
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'password_confirmation') {
            $this->passwordConfirmationTouched = true;
        }

        if (
            $this->passwordConfirmationTouched &&
            $this->password_confirmation !== '' &&
            $this->password !== $this->password_confirmation
        ) {
            $this->addError('password', 'The password field must match password confirmation');
        } else {
            $this->resetValidation('password');
        }

        $this->validateOnly($propertyName);
    }

    public function register()
    {
        if ($this->password !== $this->password_confirmation) {
            $this->addError('password', 'The password field must match password confirmation');
            return;
        }

        $validateData = $this->validate();
        $isFirstUser = User::count() === 0;

        if ($this->foto_profile) {
            $validateData['foto_profile'] = $this->foto_profile->store('profiles', 'public');
        }

        if ($this->foto_ktp) {
            $validateData['foto_ktp'] = $this->foto_ktp->store('ktp', 'public');
        }

        // Cek apakah pengguna memilih role atau tidak
        $role = $this->role ?: null;

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'phone' => $validateData['phone'] ?? null,
            'address' => $validateData['address'] ?? null,
            'foto_profile' => $validateData['foto_profile'] ?? null,
            'foto_ktp' => $validateData['foto_ktp'] ?? null,
            'facebook' => $validateData['facebook'] ?? null,
            'instagram' => $validateData['instagram'] ?? null,
            'tiktok' => $validateData['tiktok'] ?? null,
            'role' => $role,
            'is_admin' => $isFirstUser
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($isFirstUser) {
            return redirect()->intended('/admin');
        }

        return redirect()->route('home');
    }

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.layouts.app', ['hideBottomNav' => true]);
    }
}
