<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FaceEnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $user = $request->user();

        // Delete old photo if exists
        if ($user->face_photo) {
            Storage::disk('public')->delete($user->face_photo);
        }

        $path = $request->file('image')->store('faces', 'public');

        $user->update([
            'face_photo' => $path,
        ]);

        return back()->with('message', 'Face photo enrolled successfully.');
    }
}
