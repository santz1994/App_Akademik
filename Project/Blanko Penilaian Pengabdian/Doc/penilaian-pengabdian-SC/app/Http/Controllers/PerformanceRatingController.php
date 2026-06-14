<?php

namespace App\Http\Controllers;

use App\Models\PerformanceRating;
use Illuminate\Http\Request;

class PerformanceRatingController extends Controller
{
    public function index()
    {
        $data = PerformanceRating::latest()->paginate(10);
        return view('admin.performance_rating.index', compact('data'));
    }

    public function create()
    {
        $kode = 'RTG-' . str_pad(PerformanceRating::count() + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.performance_rating.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating'     => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $kode = 'RTG-' . str_pad(PerformanceRating::count() + 1, 3, '0', STR_PAD_LEFT);

        PerformanceRating::create([
            'kode_rating' => $kode,
            'rating'      => $request->rating,
            'keterangan'  => $request->keterangan,
        ]);

        return redirect()->route('admin.performance-rating.index')
            ->with('success', 'Performance rating berhasil ditambahkan.');
    }

    public function edit(PerformanceRating $performanceRating)
    {
        return view('admin.performance_rating.edit', compact('performanceRating'));
    }

    public function update(Request $request, PerformanceRating $performanceRating)
    {
        $request->validate([
            'rating'     => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $performanceRating->update($request->only('rating', 'keterangan'));

        return redirect()->route('admin.performance-rating.index')
            ->with('success', 'Performance rating berhasil diperbarui.');
    }

    public function destroy(PerformanceRating $performanceRating)
    {
        $performanceRating->delete();
        return redirect()->route('admin.performance-rating.index')
            ->with('success', 'Performance rating berhasil dihapus.');
    }
}
