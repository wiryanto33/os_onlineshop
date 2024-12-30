<div class="max-w-[480px] mx-auto bg-white min-h-screen relative shadow-lg pb-[70px]">
    <!-- Header with Back Button -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white z-50">
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100">
            <div class="flex items-center">
                <button onclick="history.back()" class="p-2 hover:bg-gray-50 rounded-full">
                    <i class="bi bi-arrow-left text-xl"></i>
                </button>
                <h1 class="ml-2 text-lg font-medium">Detail Produk</h1>
            </div>

            <a href="{{ route('shopping-cart') }}" class="relative p-2">
                <i class="bi bi-cart text-2xl"></i>

                <div
                    class="absolute -top-0 -right-1 bg-primary text-white text-xs w-5 w-5 flex items-center justify-center rounded-full">
                    {{ $cartCount }}
                </div>
            </a>
        </div>


    </div>

    <!-- Main Content -->
    <div class="pt-16">
        <!-- Product Images Slider -->
        <div class="relative bg-gray-100 h-[400px]">
            @if ($currentImage)
                <img src="{{ url('storage/' . $currentImage) }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover">
            @endif

            @if (count($images) > 1)
                <button wire:click="previousImage"
                    class="absolute left-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/50 text-white"
                    @if ($currentImageIndex == 0) disabled @endif>
                    <i class="bi bi-chevron-left"></i>
                </button>

                <button wire:click="nextImage"
                    class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/50 text-white"
                    @if ($currentImageIndex == count($images) - 1) disabled @endif>
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif

            <!-- Image Counter -->
            <div class="absolute bottom-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
                {{ $currentImageIndex + 1 }}/{{ count($images) }}
            </div>
        </div>

        <!-- Product Info -->
        <div class="p-4 border-b border-gray-100">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h2>
                    <div class="mt-1 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-primary">Rp
                            {{ number_format($product->price, 0, ',', '.') }}</span>
                        {{-- <span class="text-sm text-gray-500 line-through">Rp150.000</span> --}}
                    </div>
                </div>
                <button class="p-2 hover:bg-gray-50 rounded-full">
                    <i class="bi bi-share text-xl text-gray-600"></i>
                </button>
            </div>

            <!-- Rating & Sold -->
            <div class="flex items-center gap-4 mt-3">
                <div class="flex items-center gap-1">
                    <i class="bi bi-star-fill text-yellow-400"></i>
                    <span class="text-sm">4.8</span>
                    <span class="text-gray-500">(120)</span>
                </div>
                <div class="text-sm text-gray-500">
                    Terjual 1.2rb+
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold mb-3">Deskripsi Produk</h3>
            <div class="space-y-2 text-gray-600 text-sm">
                {!! $product->description !!}
            </div>
        </div>


        <!-- Reviews -->
        <div class="p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Ulasan Pembeli</h3>
                <a href="#" class="text-primary text-sm">Lihat Semua</a>
            </div>

            <!-- Review Items -->
            <div class="space-y-4">
                <!-- Review 1 -->
                @foreach ($testimonials as $testimonial)
                    <div class="border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div
                                class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                <img class="w-full h-full object-cover"
                                    src="{{ url('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->name }}">
                            </div>

                            <div>
                                <div class="text-sm font-medium">{{ $testimonial->name }}</div>
                                <div class="flex items-center gap-1">{{ $testimonial->rating }}
                                    <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                                    <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                                    <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                                    <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                                    <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">{{ $testimonial->message }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Navigation for Add to Cart & Buy -->
    <div
        class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-100 p-4 z-50">
        <div class="flex gap-3">
            <button wire:click = "addToCart({{ $product->id }})"
                class="flex-1 h-12 flex items-center justify-center rounded-full bg-primary text-white font-medium hover:bg-primary/90 transition-colors">
                Tambah ke Keranjang
            </button>
        </div>
    </div>
</div>
