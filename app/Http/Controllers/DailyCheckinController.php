<?php

namespace App\Http\Controllers;

use App\Models\DailyCheckin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DailyCheckinController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ontem' => ['required', 'string', 'max:5000'],
            'hoje' => ['required', 'string', 'max:5000'],
            'impedimentos' => ['nullable', 'string', 'max:5000'],
        ]);

        DailyCheckin::updateOrCreate(
            ['user_id' => auth()->id(), 'data' => today()],
            $validated,
        );

        return back()->with('success', 'Daily registrado com sucesso.');
    }
}
