<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\TextileRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UpcyclerController extends Controller
{
    protected TextileRepositoryInterface $textileRepo;

    public function __construct(TextileRepositoryInterface $textileRepo)
    {
        $this->textileRepo = $textileRepo;
    }

    /**
     * Dashboard upcycler: semua kain yang sudah diklaim
     */
    public function dashboard(): View
    {
        $claimedTextiles = $this->textileRepo->getByUpcycler(Auth::id());

        return view('upcycler.dashboard', compact('claimedTextiles'));
    }

    /**
     * Peta eksplorasi: tampilkan semua kain available di peta
     */
    public function map(): View
    {
        $availableTextiles = $this->textileRepo->getAllAvailable();

        return view('upcycler.map', compact('availableTextiles'));
    }
}