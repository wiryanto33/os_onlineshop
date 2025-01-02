<?php

namespace App\Livewire;

use App\Models\BisnisDetail;
use App\Models\BisnisPage;
use App\Models\Store;
use Livewire\Component;

class InfoBisnis extends Component
{
    public $bisnisDetails;
    public $bisnisPages;
    public $whatsapp;

    public function mount(){
        $this->bisnisDetails = BisnisDetail::all();
        $this->whatsapp = Store::first()->whatsapp;
        $this->bisnisPages = BisnisPage::first() ?? new BisnisPage(['image_content' => []]);
    }

    public function render()
    {
        return view('livewire.info-bisnis', [
            'bisniDetails' => $this->bisnisDetails,
            'bisnisPages' => $this->bisnisPages
        ]);
    }
}
