<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\PaymentMethod;
use Livewire\WithFileUploads;
use App\Models\PaymentConfirmation;

class PaymentConfirmationPage extends Component
{
    use WithFileUploads;
    public $order;
    public $paymentMethods;

    // form fields
    public $payment_method_id;
    public $source_bank_name;
    public $source_account_name;
    public $amount;
    public $transfer_date;
    public $image;

    protected $rules = [
        'payment_method_id' => 'required',
        'source_bank_name' => 'required|string|max:255',
        'source_account_name' => 'required|string',
        'amount' => 'required|numeric|min:1',
        'transfer_date' => 'required|date',
        'image' => 'required|image|max:2048', // max 10MB
    ];

    protected $messages = [
        'payment_method_id.required' => 'Pilih bank tujuan',
        'source_bank_name.required' => 'Masukkan nama bank pengirim',
        'amount.required' => 'Masukkan jumlah transfer',
        'amount.numeric' => 'Jumlah transfer harus berupa angka',
        'amount.min' => 'Jumlah transfer tidak valid',
        'transfer_date.required' => 'Pilih tanggal transfer',
        'transfer_date.date' => 'Format tanggal tidak valid',
        'image.required' => 'Upload bukti transfer',
        'image.image' => 'File harus berupa gambar',
        'image.max' => 'Ukuran file maksimal 10MB',
    ];


    public function mount($orderNumber)
    {
        $this->order = Order::where('order_number', $orderNumber)->firstOrFail();
        $this->paymentMethods = PaymentMethod::all();
        $this->amount = $this->order->total_amount;
        $this->transfer_date = now()->format('Y-m-d');
    }

    public function updateImage()
    {
        $this->validate([
            'image' => 'image|max:1024'
        ]);
    }

    public function submit()
    {
        $this->validate();

        try {
            // Upload image
            $imagePath = $this->image->store('payment-confirmations', 'public');

            // if there exist, dont add new
            $paymentConfirmation = PaymentConfirmation::where('order_id', $this->order->id)->first();

            if ($paymentConfirmation == null) {
                // Create payment confirmation
                PaymentConfirmation::create([
                    'order_id' => $this->order->id,
                    'payment_method_id' => $this->payment_method_id,
                    'source_bank_name' => $this->source_bank_name,
                    'source_account_name' => $this->source_account_name,
                    'amount' => $this->amount,
                    'transfer_date' => $this->transfer_date,
                    'image' => $imagePath,
                ]);

                $this->dispatch('showAlert', [
                    'message' => 'Success create payment confirmation',
                    'type' => 'success'
                ]);
                return redirect()->route('orders');
            } else {
                $this->dispatch('showAlert', [
                    'message' => 'Anda sudah melakukan konfirmasi pembayaran',
                    'type' => 'info'
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'message' => $e->getMessage(),
                'type' => 'danger'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.payment-confirmation', [
            'payments' => $this->paymentMethods
        ])
            ->layout('components.layouts.app', ['hideBottomNav' => true]);;
    }
}
