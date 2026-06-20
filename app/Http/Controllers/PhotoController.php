<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    /**
     * Tampilkan halaman utama / beranda
     */
    public function home()
    {
        $recent_photos = Photo::latest()->limit(8)->get();
        return view('pages.home', compact('recent_photos'));
    }

    /**
     * Tampilkan halaman galeri
     */
    public function gallery()
    {
        $photos = Photo::latest()->paginate(20);
        $total_sessions = Photo::distinct('session_id')->count('session_id');
        $today_count = Photo::whereDate('created_at', today())->count();

        return view('pages.gallery', compact('photos', 'total_sessions', 'today_count'));
    }

    /**
     * Tampilkan halaman photobooth
     */
    public function photobooth()
    {
        $frames = $this->getFrames();
        return view('pages.photobooth', compact('frames'));
    }

    /**
     * Simpan foto dari photobooth ke storage & database
     */
    public function store(Request $request)
    {
        $request->validate([
            'photos'   => 'required|array|min:1|max:10',
            'photos.*' => 'required|string',
            'filter'   => 'nullable|string|max:50',
            'frame_id' => 'nullable|integer',
        ]);

        $sessionId = Str::uuid();
        $savedPhotos = [];

        foreach ($request->photos as $photoData) {
            // Decode base64
            if (!preg_match('/^data:image\/(\w+);base64,/', $photoData, $type)) {
                continue;
            }

            $imageData = substr($photoData, strpos($photoData, ',') + 1);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                continue;
            }

            // Generate filename
            $filename = 'photos/' . $sessionId . '/' . Str::random(12) . '.jpg';

            // Simpan ke storage
            Storage::disk('public')->put($filename, $imageData);

            // Simpan ke database
            $photo = Photo::create([
                'file_path'  => $filename,
                'session_id' => $sessionId,
                'filter'     => $request->filter ?? 'normal',
                'frame_id'   => $request->frame_id,
            ]);

            $savedPhotos[] = $photo;
        }

        if (empty($savedPhotos)) {
            return response()->json(['message' => 'Tidak ada foto yang valid'], 422);
        }

        return response()->json([
            'message'    => 'Foto berhasil disimpan',
            'session_id' => $sessionId,
            'count'      => count($savedPhotos),
        ], 201);
    }

    /**
     * Hapus foto dari storage & database
     */
    public function destroy(Photo $photo)
    {
        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return response()->json(['message' => 'Foto berhasil dihapus']);
    }

    /**
     * Daftar frame yang tersedia
     */
    private function getFrames(): array
    {
        return [
            ['id' => 1,  'name' => 'Classic',   'preview_color' => '#C9A96E'],
            ['id' => 2,  'name' => 'Hitam',      'preview_color' => '#1A1A1A'],
            ['id' => 3,  'name' => 'Putih',      'preview_color' => '#F5F0E8'],
            ['id' => 4,  'name' => 'Merah Muda', 'preview_color' => '#E8A0A0'],
            ['id' => 5,  'name' => 'Biru',       'preview_color' => '#7BA7BC'],
            ['id' => 6,  'name' => 'Hijau',      'preview_color' => '#6B9E78'],
        ];
    }
}