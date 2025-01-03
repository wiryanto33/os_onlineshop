<div class="max-w-[480px] mx-auto bg-white min-h-screen relative shadow-lg">
    <!-- Header -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white z-50 shadow-md">
        <div class="flex items-center h-16 px-4 border-b border-gray-200">
            <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-full">
                <i class="bi bi-arrow-left text-xl text-gray-600"></i>
            </button>
            <h1 class="ml-4 text-lg font-bold text-gray-800">Informasi Bisnis</h1>
        </div>
    </div>

    <!-- Banner Slider -->
    <div class="pt-20 px-4">
        @if (!empty($bisnisPages->image_content))
            <div class="swiper-container rounded-xl overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($bisnisPages->image_content as $item)
                        <div class="swiper-slide">
                            <div class="w-full">
                                <img src="{{ url('storage/' . $item) }}" alt="Banner"
                                    class="w-full rounded-xl object-cover">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-center text-gray-500 mt-4">No images available.</p>
        @endif
    </div>

    <!-- Informasi Bisnis Content -->
    <div class="mt-6 pb-20 bg-gray-100">
        <div class="pt-10 px-6 space-y-6">
            <!-- Business Card -->
            @foreach ($bisnisDetails as $bisnisDetail)
                <div class="border border-gray-300 rounded-2xl overflow-hidden shadow-lg bg-white">
                    <!-- Header -->
                    <div class="p-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-shop text-primary text-xl"></i>
                                <h3 class="font-semibold text-lg text-gray-800">{{ $bisnisDetail->bisnis_name }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <div class="flex gap-4">
                            <img src="{{ url('/storage/' . $bisnisDetail->bisnis_image) }}"
                                alt="{{ $bisnisDetail->bisnis_name }}"
                                class="w-24 h-24 object-cover rounded-lg shadow-sm">

                            <div class="flex-1">
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700">Syarat:</h4>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                        @foreach ($bisnisDetail->bisnisRules as $bisnisRule)
                                            <li>{{ $bisnisRule->rules }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="font-medium text-gray-700">Keuntungan:</h4>
                                    <ul class="list-disc list-inside text-sm text-gray-600 space-y-2">
                                        @foreach ($bisnisDetail->bisnisBenefits as $bisnisBenefit)
                                            <li>{{ $bisnisBenefit->benefits }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-5 border-t border-gray-200 bg-gray-50">
                        <a href="https://wa.me/{{ $whatsapp }}" target="_blank"
                            class="flex items-center justify-between p-4 bg-primary rounded-xl hover:bg-primary/90 transition duration-300 max-w-[250px] mx-auto">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-telephone text-white"></i>
                                <span class="text-white">Daftar Via Whatsapp</span>
                            </div>
                            <i class="bi bi-chevron-right text-white text-400"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const swiper = new Swiper('.swiper-container', {
                loop: true, // Memutar slider
                autoplay: {
                    delay: 3000, // Durasi autoplay dalam milidetik
                    disableOnInteraction: false, // Tetap autoplay setelah interaksi
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                slidesPerView: 1, // Jumlah slide yang terlihat
                spaceBetween: 10, // Jarak antar slide
                effect: 'fade', // Efek transisi lebih halus
            });
        });
    </script>
@endpush
