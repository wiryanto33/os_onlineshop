<div class="max-w-[480px] mx-auto bg-white min-h-screen relative shadow-lg pb-[70px]">
    <!-- Banner -->
    {{-- <div class="h-[180px] relative overflow-hidden bg-gradient-to-br from-primary to-secondary">
        <div class="absolute inset-0 opacity-50 pattern-dots"></div>
    </div> --}}
    <div class="relative h-[280px] overflow-hidden">
        <!-- Banner Slider main container -->
        <div id="BannerSlider" class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                @if (!empty($store->banner) && is_array($store->banner))
                    @foreach ($store->banner as $item)
                        <div
                            class="swiper-slide bg-gradient-to-br from-pink-500 to-red-500 flex items-center justify-center">
                            <img src="{{ url('storage/' . $item) }}" alt="banner" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                @else
                    <p>No banners available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="px-5 relative -mt-10 z-10">
        <div class="w-[120px] h-[120px]  rounded-[20px] flex items-center justify-center  transform rotate-[5deg]">
            <img src="{{ $store->imageUrl }}" alt="Logo" class="w-[100px] h-[100px] transform -rotate-[25deg]">
        </div>
        <h4 class="mt-3 mb-1 text-gray-800 font-semibold text-xl">{{ $store->name }}</h4>
        <p class="text-gray-800 text-md  leading-relaxed text">
            {{ $store->description }}
        </p>
    </div>

    <div id="HeroSlider" class="swiper w-full  mt-5">
        <div class="swiper-wrapper">
            @foreach ($store->info_swiper as $item)
                <div class="swiper-slide !w-fit mr-5">
                    <a href="">
                        <div class="flex h-[190px] w-[320px] items-center justify-center overflow-hidden rounded-3xl">
                            <img src="{{ url('storage/' . $item) }}" alt="image"
                                class="h-full w-full object-cover" />
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mt-5 px-2.5 overflow-x-auto hide-scrollbar">
        <div class="flex gap-2.5 pb-2.5 whitespace-nowrap">

            <button wire:click="setCategory('all')"
                class="px-6 h-10 flex items-center rounded-full transition-colors border {{ $selectedCategory == 'all' ? 'bg-primary text-white border border-primary' : 'text-gray-600 border-gray-200 hover:border-primary hover:text-primary' }} ">
                Semua
            </button>
            @foreach ($categories as $item)
                <button wire:click="setCategory('{{ $item->id }}')"
                    class="px-6 h-10 flex items-center rounded-full transition-colors border {{ $selectedCategory == $item->id ? 'bg-primary text-white border border-primary' : 'text-gray-600 border-gray-200 hover:border-primary hover:text-primary' }}">
                    {{ $item->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="p-3">
        <div class="grid grid-cols-2 gap-3">
            <!-- Product Card  -->
            @foreach ($products as $item)
                <div
                    class="bg-white rounded-2xl overflow-hidden shadow-sm hover:-translate-y-1 transition-transform duration-300">

                    <a href="{{ route('product.detail', ['slug' => $item->slug]) }}" wire:navigate>
                        <div class="relative">
                            <span
                                class="absolute top-2.5 left-2.5 bg-primary/90 text-white text-xs font-medium px-3 py-1 rounded-full">
                                Baru
                            </span>
                            <img src="{{ $item->firstImageUrl }}" alt="onesevenstore"
                                class="w-full h-[180px] object-cover">
                        </div>
                    </a>
                    <div class="p-3">
                        <h6 class="text-sm font-medium text-gray-700 line-clamp-2">{{ $item->name }}</h6>
                        <div class="mt-2 flex items-center gap-1">
                            <span class="text-xs text-gray-500">Rp</span>
                            <span
                                class="text-primary font-semibold">{{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Advertise Card -->
            @endforeach
        </div>
    </div>

    <div class="mt-5 px-5 pb-5">
        <a href="{{ route('reward') }}">
            <div class="bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl overflow-hidden relative shadow-md">
                <img src="{{ url('storage/' . $store->advertise) }}" alt="Advertise Banner"
                    class="w-full h-full object-cover">
            </div>
        </a>
    </div>

</div>

@push('scripts')
    <script>
        // Untuk Banner dengan Slide Otomatis
        const bannerSwiper = new Swiper('#BannerSlider', {
            loop: true,
            autoplay: {
                delay: 4000, // jeda otomatis dalam milidetik
                disableOnInteraction: false, // slide tetap berlanjut meski pengguna berinteraksi
            },
        });

        // Untuk HeroSlider dengan Scroll Manual
        const heroSwiper = new Swiper('#HeroSlider', {
            loop: true, // nonaktifkan looping jika tidak diperlukan
            autoplay: false, // nonaktifkan autoplay
            slidesPerView: 'auto', // agar bisa di-scroll manual
            spaceBetween: 20, // jarak antar slide
            scrollbar: {
                el: '.swiper-scrollbar', // menambahkan scrollbar jika perlu
                draggable: true,
            },
        });
    </script>
@endpush
