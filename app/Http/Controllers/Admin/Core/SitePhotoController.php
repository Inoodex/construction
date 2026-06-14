<?php

namespace App\Http\Controllers\Admin\Core;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\SitePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SitePhotoController extends Controller
{
    public function globalIndex()
    {
        $photos = SitePhoto::with('site.project', 'uploader')->latest()->paginate(24);
        return view('admin.core.site-photos.global_index', compact('photos'));
    }

    public function index(Site $site)
    {
        $photos = $site->photos()->with('uploader')->latest()->paginate(24);
        return view('admin.core.site-photos.index', compact('site', 'photos'));
    }

    public function store(Request $request, Site $site)
    {
        $validated = $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        foreach ($request->file('photos') as $file) {
            $path = $file->store('uploads/site-photos', 'public');

            $site->photos()->create([
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'caption' => $request->caption,
                'uploaded_by' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.core.sites.photos.index', $site)
            ->with('success', 'Photos uploaded successfully.');
    }

    public function destroy(Site $site, SitePhoto $photo)
    {
        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return redirect()->route('admin.core.sites.photos.index', $site)
            ->with('success', 'Photo deleted successfully.');
    }

    public function updateCaption(Request $request, Site $site, SitePhoto $photo)
    {
        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $photo->update($validated);

        return redirect()->route('admin.core.sites.photos.index', $site)
            ->with('success', 'Caption updated.');
    }
}
