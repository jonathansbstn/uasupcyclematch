<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UpcyclerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminVerificationController extends Controller
{
    public function index()
    {
        $upcyclers = User::where('role', 'upcycler')
            ->with('upcyclerProfile')
            ->latest()
            ->paginate(20);

        return view('admin.upcycler-verification', compact('upcyclers'));
    }

    public function verify(Request $request, User $user)
    {
        $user->update(['is_verified' => true]);

        UpcyclerProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['verification_status' => 'verified', 'verified_at' => now()]
        );

        return back()->with('success', "Akun {$user->name} berhasil diverifikasi.");
    }

    public function reject(Request $request, User $user)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $user->update(['is_verified' => false]);

        UpcyclerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'verification_status' => 'rejected',
                'rejection_reason'    => $request->reason ?? 'Tidak memenuhi persyaratan.',
            ]
        );

        return back()->with('success', "Akun {$user->name} telah ditolak.");
    }
}
