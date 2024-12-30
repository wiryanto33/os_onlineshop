<div class="max-w-[480px] mx-auto bg-white min-h-screen relative shadow-lg pb-[140px]">
    <!-- Header -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white z-50">
        <div class="flex items-center h-16 px-4 border-b border-gray-100">
            <button onclick="history.back()" class="p-2 hover:bg-gray-50 rounded-full">
                <i class="bi bi-arrow-left text-xl"></i>
            </button>
            <h1 class="ml-2 text-lg font-medium">Checkout</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="pt-20 pb-12 px-4">
        <div id="snap-container"></div>
        <!-- Order Summary -->
        <div class="mb-6">
            <h2 class="text-lg font-medium mb-4">Ringkasan Pesanan</h2>
            <div class="space-y-4">
                @foreach ($carts as $cart)
                    <!-- Sample Product 1 -->
                    <div class="flex gap-3">
                        <img src="{{ $cart->product->first_image_url }}" alt="Product 1"
                            class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium line-clamp-2">{{ $cart->product->name }}</h3>
                            <div class="text-sm text-gray-500 mt-1">{{ $cart->quantity }} x Rp
                                {{ number_format($cart->product->price, 0, ',', '.') }}</div>
                            <div class="text-primary font-medium">Rp {{ $cart->product->price * $cart->quantity }}</div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- Shipping Form -->
        <div class="space-y-4">
            <h2 class="text-lg font-medium">Data Penerima</h2>

            <!-- Recipient Info -->
            <div>
                <input type="text" wire:model="shippingData.recipient_name"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="Nama lengkap penerima">
            </div>

            <div>
                <input
                    wire:model.live="shippingData.phone"
                    type="tel"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary"
                    placeholder="Contoh: 08123456789">
            </div>

            <!-- Address -->
            <div>
                <select wire:model.live="shippingData.province_id" wire:click="loadProvinces"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Provinsi</option>
                    @foreach ($provinces as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live = "shippingData.city_id" @if (!$shippingData['province_id']) disabled @endif
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Kabupaten/Kota</option>
                    @foreach ($cities as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="starterShipping"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Ekspedisi</option>
                    <option value="jne">JNE</option>
                    <option value="pos">POS</option>
                    <option value="tiki">TIKI</option>
                </select>
            </div>
            <div>
                <select wire:model.live="selectedService"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Pilih Layanan Pengiriman</option>
                    @foreach ($shippingServices as $service)
                        <option
                            value="{{ json_encode([
                                'code' => $service['code'],
                                'service' => $service['service'],
                                'etd' => $service['etd'],
                                'cost' => $service['cost'],
                                'description' => $service['description'],
                                'name' => $service['name'],
                            ]) }}">
                            {{ $service['name'] }} - {{ $service['service'] }}
                            {{ $service['etd'] }} hari - Rp. {{ number_format($service['cost']) }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if ($isPro)
                <div>
                    <select
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Pilih Kecamatan</option>
                        <option value="1">Kebayoran Baru</option>
                        <option value="2">Setia Budi</option>
                        <option value="3">Mampang Prapatan</option>
                    </select>
                </div>
            @endif


            <div>
                <textarea
                    wire:model.live="shippingData.address_detail"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary"
                    rows="3" placeholder="Detail Alamat Nama jalan, nomor rumah, RT/RW, patokan"></textarea>
            </div>

            <div class="space-y-4 mt-8">


                <div>
                    <textarea
                        wire:model.live="shippingData.noted"
                        class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary"
                        rows="2" placeholder="Catatan untuk pengiriman (opsional)"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div
        class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-100 p-4 z-50">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-gray-600">Total Pembayaran:</p>
                <p class="text-lg font-semibold text-primary">Rp {{ number_format($total + $shippingCost) }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">3 Produk</p>
            </div>
        </div>

        <button
            wire:click="createOrder"
            class="w-full h-12 flex items-center justify-center rounded-full bg-primary text-white font-medium hover:bg-primary/90 transition-colors">
            Buat Pesanan
        </button>
    </div>
</div>

@push('scripts')
    <!-- Include Midtrans Snap -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('payment-success', (data) => {
                console.log('Payment event received:', data);
                // Data adalah array, ambil elemen pertama
                const snapToken = data[0].snap_token;
                const orderId = data[0].order_id;

                console.log('Using snap token:', snapToken);

                if (snapToken) {
                    try {
                        window.snap.pay(snapToken, {
                            onSuccess: function(result) {
                                console.log('success', result);
                                window.location.href = `/order-detail/${orderId}`;
                            },
                            onPending: function(result) {
                                console.log('pending', result);
                                window.location.href = `/order-detail/${orderId}`;
                            },
                            onError: function(result) {
                                console.error('error', result);
                                alert('Pembayaran gagal! Silakan coba lagi.');
                            },
                            onClose: function() {
                                console.log('customer closed the popup without finishing the payment');
                                alert('Anda menutup halaman pembayaran sebelum menyelesaikan transaksi');
                                window.location.href = `/`;
                            }
                        });
                    } catch (error) {
                        console.error('Snap pay error:', error);
                        alert('Terjadi kesalahan saat membuka popup pembayaran');
                    }
                } else {
                    console.error('No snap token received:', data);
                }
            });
        });
    </script>
@endpush

