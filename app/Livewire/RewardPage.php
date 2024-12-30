<?php

namespace App\Livewire;

use App\Models\Reward;
use Livewire\Component;

class RewardPage extends Component
{
    public $rewards;

    public function mount() {
        $this->rewards = Reward::all();
    }
    public function render()
    {
        return view('livewire.reward', [
            'rewards' => $this->rewards
        ]);
    }
}
