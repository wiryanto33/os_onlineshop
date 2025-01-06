<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\RajaOngkirSetting;
use App\Models\Store;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use App\Services\MidtransService;
use App\Services\RajaOngkirService;

class Checkout extends Component
{

    public $carts = [];
    public $total = 0;
    public $isPro = false;
    public $provinces = [];
    public $cities = [];
    public $subdistricts = [];
    public $isLoadingProvinces = false;
    public $shippingServices = [];
    public $totalItems = 0;
    public $starterShipping;
    public $mainShipping;
    public $selectedService = null;
    public $shippingCost = 0;
    public $store;

    protected $rajaongkir;
    protected $midtrans;

    public $shippingData = [
        'recipient_name' => '',
        'phone' => '',
        'province_id' => '',
        'city_id' => '',
        'subdistrict_id' => '',
        'address_detail' => '',
        'noted' => ''
    ];

    protected $rules = [
        'shippingData.recipient_name' => 'required|min:3',
        'shippingData.phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'shippingData.province_id' => 'required',
        'shippingData.city_id' => 'required',
        'shippingData.subdistrict_id' => 'required_if:isPro,true',
        'shippingData.address_detail' => 'required|min:10',
        'selectedCourier' => 'required',
        'selectedService' => 'required'
    ];

    public function boot(RajaOngkirService $rajaongkir, MidtransService $midtrans)
    {
        $this->rajaongkir = $rajaongkir;
        $this->midtrans = $midtrans;
    }

    public function mount()
    {
        $this->loadCarts();
        if ($this->carts->isEmpty()) {
            return redirect()->route('home');
        }
        $this->store = Store::first();
        $this->isPro = RajaOngkirSetting::getActive()->isPro();

        if (auth()->check()) {
            $user = auth()->user();
            $this->shippingData['recipient_name'] = $user->name;
        }
    }

    public function loadCarts()
    {
        $this->carts = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $this->calculateTotal();
    }

    public function loadProvinces()
    {
        if (empty($this->provinces)) {
            // $this->isLoadingProvinces = true;
            $this->provinces = $this->rajaongkir->getProvinces();
            // $this->isLoadingProvinces = false;
        }
    }

    public function updatedShippingDataProvinceId($value)
    {

        if ($value) {
            $this->cities = $this->rajaongkir->getCities($value);
        }
    }

    public function updatedShippingDataCityId($value)
    {
        if ($value && $this->isPro) {
            $this->subdistricts = $this->rajaongkir->getSubdistricts($value);
            $this->shippingData['subdistrict_id'] = '';
        }
    }

    public function updatedStarterShipping($value)
    {
        if ($value && $this->shippingData['city_id']) {
            $this->mainShipping = $value;
            $this->loadShippingServices();
        }
    }

    public function updatedShippingDataSubdistrictId($value)
    {
        if ($value) {
            $this->loadShippingServices();
        }
    }

    public function updatedSelectedService($value)
    {
        if ($value) {
            $serviceData = json_decode($value, true);
            $this->shippingCost = $serviceData['cost'] ?? 0;
        }
    }

    private function getWeight()
    {
        return $this->carts->sum(fn($cart) => ($cart->product->weight ?? 1000) * $cart->quantity);
    }

    private function loadShippingServices()
    {
        $setting = RajaOngkirSetting::getActive();
        $store = Store::first();

        try {
            if (!$store || !$store->regency_id) {
                throw new \Exception('Store location is not configured');
            }

            $this->shippingServices = $this->rajaongkir->getCost(
                $this->isPro ? $store->subdistrict_id : $store->regency_id,
                $this->isPro ? 'subdistrict' : 'city',
                $this->isPro ? $this->shippingData['subdistrict_id'] : $this->shippingData['city_id'],
                $this->isPro ? 'subdistrict' : 'city',
                $this->getWeight(),
                $this->isPro ? $setting->couriers : $this->mainShipping
            );
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'message' => 'Gagal memuat biaya pengiriman ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function calculateTotal()
    {
        $this->total = 0;
        $this->totalItems = 0;

        foreach ($this->carts as $cart) {
            $this->total += $cart->product->price * $cart->quantity;
            $this->totalItems += $cart->quantity;
        }
    }

    public function render()
    {
        if ($this->carts->isEmpty()) {
            return redirect()->route('home');
        }
        return view('livewire.checkout')
            ->layout('components.layouts.app', ['hideBottomNav' => true]);
    }

    public function createOrder()
    {

        if (!$this->carts->isEmpty()) {
            try {

                if ($this->selectedService != null) {
                    $serviceData = json_decode($this->selectedService, true);
                } else {

                    $this->dispatch('showAlert', [
                        'message' => 'Mohon isi alamat tujuan dan ekspedisi',
                        'type' => 'error'
                    ]);
                    return;
                }

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'subtotal' => $this->total,
                    'total_amount' => $this->total + $serviceData['cost'],
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'recipient_name' => $this->shippingData['recipient_name'],
                    'phone' => $this->shippingData['phone'],
                    'province' => $this->provinces[$this->shippingData['province_id']],
                    'city' => $this->cities[$this->shippingData['city_id']],
                    'subdistrict' => $this->isPro && !empty($this->shippingData['subdistrict_id'])
                        ? $this->subdistricts[$this->shippingData['subdistrict_id']]['name']
                        : null,
                    'address_detail' => $this->shippingData['address_detail'],
                    'shipping_code' => $serviceData['code'],
                    'shipping_service' => $serviceData['service'],
                    'shipping_description' => $serviceData['description'],
                    'shipping_cost' => $serviceData['cost'],
                    'shipping_etd' => $serviceData['etd'],
                    'noted' => $this->shippingData['noted']
                ]);

                foreach ($this->carts as $cart) {
                    $order->items()->create([
                        'product_id' => $cart->product_id,
                        'product_name' => $cart->product->name,
                        'quantity' => $cart->quantity,
                        'price' => $cart->product->price
                    ]);
                }

                Cart::where('user_id', auth()->id())->delete();

                Notification::route('mail', $this->store->email_notification)
                    ->notify(new NewOrderNotification($order));

                //handle peyment choice
                if ($this->store->payment_gateway) {

                    $result = $this->midtrans->createTransaction($order, $order->items);

                    if (!$result['success']) {
                        throw new \Exception($result['message']);
                    }

                    $order->update([
                        'snap_token' => $result['token']
                    ]);

                    $this->dispatch('payment-success', [
                        'snap_token' => $result['token'],
                        'order_id' =>  $order->order_number
                    ]);
                } else {
                    return redirect()->route('orders');
                }
            } catch (\Exception $e) {
                $this->dispatch('showAlert', [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ]);
            }
        } else {
            $this->dispatch('showAlert', [
                'message' => 'Keranjang belanja kosong',
                'type' => 'error'
            ]);
        }
    }
}
