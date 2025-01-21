<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function downloadCard()
    {
        $user = auth()->user(); // Ambil data user saat ini
        $store = Store::first();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => strtoupper($user->role),
            'address' => $user->address,
            'foto_profile' => $user->foto_profile ? storage_path('app/public/' . $user->foto_profile) : null,
            'card_back' => $store->card_back ? storage_path('app/public/' . $store->card_back) : null,
            'card_front' => $store->card_front ? storage_path('app/public/' . $store->card_front) : null
        ];

        // dd($data);

        $pdf = Pdf::loadView('member.card', $data);
        return $pdf->stream('member.pdf');
        // return $pdf->download('member_card.pdf');
    }
}
