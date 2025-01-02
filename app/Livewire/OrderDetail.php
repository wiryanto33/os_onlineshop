<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\Store;
use App\Services\MidtransService;
use Carbon\Carbon;
use App\Services\OrderStatusService;

class OrderDetail extends Component
{
    public $order;
    public $paymentDeadline;
    public $paymentMethods;
    public $store;

    public function mount($orderNumber)
    {
        $this->order = Order::where('order_number', $orderNumber)
            ->firstOrFail();
        $this->paymentDeadline = Carbon::parse($this->order->created_at)->addHours(12);
        $this->paymentMethods = PaymentMethod::all();
        $this->store = Store::first();
    }

    public function getStatusInfo()
    {
        return OrderStatusService::getStatusInfo(
            $this->order->status,
            $this->order->shipping_number,
            $this->paymentDeadline,
            $this->order->completed_at
        );
    }

    public function render()
    {
        $statusInfo = $this->getStatusInfo();
        $this->checkPaymentStatus();
        return view('livewire.order-detail', [
            'statusInfo' => $statusInfo
        ])->layout('components.layouts.app', ['hideBottomNav' => true]);;
    }

    public function checkPaymentStatus()
    {
        if ($this->order && $this->store->payment_gateway) {
            try {

                $midtrans = app(MidtransService::class);
                $status = $midtrans->getStatus($this->order);

                $this->order->update([
                    'detail_transaction' => json_encode($status['data'])
                ]);

                if ($status['success']) {
                    switch ($status['data']->transaction_status) {
                        case 'settlement':
                            $this->order->update([
                                'payment_status' => 'paid',
                                'status' => 'processing',
                            ]);
                            break;
                        case 'deny':
                            $this->order->update([
                                'status' => 'cancelled',
                            ]);
                            break;
                        case 'cancel':
                            $this->order->update([
                                'status' => 'cancelled',
                            ]);
                            break;
                        case 'expire':
                            $this->order->update([
                                'status' => 'cancelled',
                            ]);
                            break;
                    }
                } else {
                    $this->order->update([
                        'status' => 'cancelled',
                    ]);
                }
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
