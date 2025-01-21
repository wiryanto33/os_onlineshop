<div class="max-w-[480px] mx-auto bg-white min-h-screen relative shadow-lg">
    <!-- Header -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white z-50">
        <div class="flex items-center h-16 px-4 border-b border-gray-100">
            <button onclick="history.back()" class="p-2 hover:bg-gray-50 rounded-full">
                <i class="bi bi-arrow-left text-xl"></i>
            </button>
            <h1 class="ml-2 text-lg font-medium">Profil Saya</h1>
        </div>
    </div>

    <!-- Profile Content -->
    < class="pt-16">
        <!-- Profile Header -->
        <div class="bg-gradient-to-br from-primary to-secondary p-6">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mt-10">
                    @if ($foto_profile)
                        <img src="{{ asset('storage/' . $foto_profile) }}" alt="Foto Profil"
                            class="w-full h-full rounded-full object-cover">
                    @else
                        <i class="bi bi-person text-3xl text-white"></i>
                    @endif
                </div>
                <div class="text-white">
                    <h2 class="text-xl font-semibold">{{ $name }}</h2>
                    <p class="text-white/80">{{ $email }}</p>
                </div>
            </div>
        </div>

        <!-- User Point -->
        <div class="bg-white px-4 py-6 shadow-md rounded-lg mx-4 -mt-3 relative z-10">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Poin Pembelanjaan</h3>
                <h3 class="text-xl font-semibold"> {{ strtoupper($role) }}</h3>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <p class="text-4xl font-bold text-primary">{{ $point }}</p>
                    <p class="text-sm text-gray-500">Poin Anda</p>
                </div>
            </div>
        </div>

        <!-- Profile Menu -->
        <div class="p-4 space-y-4">
            <!-- Account Settings -->
            <div class="space-y-2">
                <h3 class="text-sm font-medium text-gray-500">Akun</h3>
                <div class="space-y-1">
                    @if (in_array($role, ['distributor', 'agent', 'stokist', 'reseller']))
                        <a href="{{ route('download-card') }}"
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-download text-primary"></i>
                                <span>Download Card Member</span>
                            </div>
                            <i class="bi bi-chevron-right text-gray-400"></i>
                        </a>
                    @else
                        <span class="flex items-center justify-between p-4 bg-gray-50 rounded-xl text-gray-400">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-download text-gray-400"></i>
                                <span>Download Card Member</span>
                            </div>
                            <i class="bi bi-lock text-gray-400"></i>
                        </span>
                    @endif
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank"
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-question-circle text-primary"></i>
                            <span>Hubungi Via Whatsaap</span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-400"></i>
                    </a>
                    <a href="#"
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-shield-lock text-primary"></i>
                            <span>Tentang Aplikasi</span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-400"></i>
                    </a>
                </div>
            </div>



            <!-- Logout Button -->
            <button wire:click="logout"
                class="w-full mt-6 p-4 text-red-500 flex items-center justify-center gap-2 bg-red-50 rounded-xl hover:bg-red-100">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </div>
</div>
</div>
