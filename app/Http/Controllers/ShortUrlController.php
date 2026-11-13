<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', ShortUrl::class);

        $shortUrls = ShortUrl::with('user')->visibleTo(auth()->user())->latest()->paginate(10);

        return view('short-urls.index', compact('shortUrls'));
    }

    public function create()
    {
        $this->authorize('create', ShortUrl::class);

        return view('short-urls.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', ShortUrl::class);

        $validated = $request->validate([
            'original_url' => 'required|url'
        ]);

        $shortUrl = ShortUrl::create([
            'user_id' => auth()->id(),
            'original_url'=> $validated['original_url'],
            'short_code' => $this->generateShortCode()
        ]);

        return redirect()->route('short-urls.index')->with('success', 'Short URL created successfully')->with('short_link', url('/s/'.$shortUrl->short_code));
    }

    private function generateShortCode()
    {
        do {
            $code = Str::random(6);
        } while(ShortUrl::where('short_code', $code)->exists());

        return $code;
    }
}
