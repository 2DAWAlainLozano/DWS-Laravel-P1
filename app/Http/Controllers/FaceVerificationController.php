<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FaceVerificationController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/FaceVerification');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'webcam_image' => 'required|file',
        ]);

        $user = $request->user();

        if (!$user->face_photo || !Storage::disk('public')->exists($user->face_photo)) {
            return back()->withErrors(['message' => 'No tienes un rostro enrolado para verificar. Por favor, ve a tu perfil y regístralo primero.']);
        }

        try {
            $response = Http::attach(
                'img1', file_get_contents(Storage::disk('public')->path($user->face_photo)), 'enrolled.jpg'
            )->attach(
                'img2', $request->file('webcam_image')->get(), 'webcam.jpg'
            )->post('http://face-service:5000/verify');

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['verified']) && $data['verified'] === true) {
                    $request->session()->put('face_verified', true);
                    
                    $intended = $request->session()->pull('url.intended', route('dashboard'));
                    return redirect()->to($intended);
                }
            }
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Error conectando con el servicio de verificación: ' . $e->getMessage()]);
        }

        return back()->withErrors(['message' => 'La verificación facial ha fallado o el rostro no concuerda.']);
    }
}
