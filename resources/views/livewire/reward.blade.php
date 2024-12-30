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
        @foreach ($rewards as $reward)
            <div class="space-y-4">
                <!-- Sepeda Motor -->
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl shadow-md hover:bg-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <img src="{{ url('storage/' . $reward->image) }}" alt="{{$reward->name}}">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{$reward->name}}</h3>
                            <p class="text-sm text-gray-500">{{$reward->point}} Poin</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">{{$reward->description}}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Note -->
        <div class="bg-primary/10 border border-primary p-4 rounded-lg flex items-start gap-3 shadow-md">
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

    </div>
</div>
