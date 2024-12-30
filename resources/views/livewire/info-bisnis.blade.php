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
        <div class="w-full overflow-hidden rounded-xl shadow-md">
            <div class="swiper-container">
                <img src="{{ url('/storage/' . $bisnisPages->image_content) }}" alt="Banner 1" class="w-full">
            </div>
        </div>
    </div>

    <!-- Informasi Bisnis Content -->
    <div class="mt-3 pb-20 bg-gray-100 min-h-screen">
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
                                <div class="mb-3">
                                    <h4 class="font-medium text-gray-700">Syarat:</h4>
                                    <ul class="list-disc list-inside text-sm text-gray-600">
                                        @foreach ($bisnisDetail->bisnisRules as $bisnisRule)
                                            <li>{{ $bisnisRule->rules }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="font-medium text-gray-700">Keuntungan:</h4>
                                    <ul class="list-disc list-inside text-sm text-gray-600">
                                        @foreach ($bisnisDetail->bisnisBenefits as $bisnisBenefit)
                                            <li>{{ $bisnisBenefit->benefits }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-5 border-t border-gray-200 bg-gray-50 flex justify-end">
                        <button
                            class="px-6 py-2 text-sm font-medium bg-primary text-white rounded-full shadow hover:bg-primary/90 transition-all">
                            Join Us
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
