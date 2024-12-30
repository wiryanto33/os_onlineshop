<?php

namespace App\Livewire;

use App\Models\BisnisDetail;
use App\Models\BisnisPage;
use Livewire\Component;

class InfoBisnis extends Component
{
    public $bisnisDetails;
    public $bisnisPages;

    public function mount(){
        $this->bisnisDetails = BisnisDetail::all();
        $this->bisnisPages = BisnisPage::first();
    }

    public function render()
    {
        return view('livewire.info-bisnis', [
            'bisniDetails' => $this->bisnisDetails,
            'bisnisPages' => $this->bisnisPages
        ]);
    }
}
