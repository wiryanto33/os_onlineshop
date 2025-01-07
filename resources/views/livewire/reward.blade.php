<div class="max-w-[480px] mx-auto bg-white min-h-screen relative shadow-lg">
    <!-- Header -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white z-50">
        <div class="flex items-center h-16 px-4 border-b border-gray-100">
            <button onclick="history.back()" class="p-2 hover:bg-gray-50 rounded-full">
                <i class="bi bi-arrow-left text-xl"></i>
            </button>
            <h1 class="ml-2 text-lg font-medium">Hadiah Oneseven Store</h1>
        </div>
    </div>

    <!-- Reward Content -->
    <div class="pt-16 pb-6 px-4 space-y-6">
        <!-- Reward List -->
        @forelse ($rewards as $reward)
            <div class="space-y-4">
                <div class="items-center bg-gray-50 p-4 rounded-xl shadow-md hover:bg-gray-100">
                    <div class="items-center gap-4">
                        <div class="w-50 h-50 bg-gray-200 rounded-lg flex items-center justify-center">
                            @if ($reward->image && file_exists(public_path('storage/' . $reward->image)))
                                <img src="{{ url('storage/' . $reward->image) }}" alt="{{ $reward->name }}">
                            @else
                                <p class="text-sm text-gray-500">Gambar belum tersedia</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $reward->name }}</h3>
                        <span class="px-3 py-1 text-sm font-medium text-white bg-yellow-600 rounded-full">
                            {{ $reward->point }} POINT
                        </span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">{{ $reward->description }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">Reward OneSevenStore belum tersedia</p>
        @endforelse


        <!-- Note -->

        <div class="bg-primary/10 border border-primary p-4 rounded-lg flex items-start gap-3 shadow-md ">
            <div class="w-20 h-8 bg-primary rounded-full flex items-center justify-center text-white">
                <i class="bi bi-gift text-xl"></i>
            </div>
            <div class="text-gray-800">
                <h4 class="text-base font-semibold text-primary">Kumpulkan Poin</h4>
                <p class="text-sm">
                    Setiap pembelian <strong>1 Karton Product OneSeven</strong> akan mendapatkan <strong>1
                        poin</strong>. Kumpulkan poinmu dan klaim reward tanpa diundi!
                </p>
            </div>
        </div>
        <div>
            <br>
            <br>
        </div>
    </div>
</div>
