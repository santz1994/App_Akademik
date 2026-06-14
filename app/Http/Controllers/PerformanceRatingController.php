<?php

namespace App\Http\Controllers;

use App\Models\PerformanceRating;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PerformanceRatingController extends Controller
{
    public function index(Request $request)
    {
        $data = PerformanceRating::latest();
        $data = $this->paginateWithPerPage($data, $request, 10);

        return view('admin.performance_rating.index', compact('data'));
    }

    public function create()
    {
        $kode = $this->generateNextKodeRating();

        return view('admin.performance_rating.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                PerformanceRating::create([
                    'kode_rating' => $this->generateNextKodeRating(),
                    'rating' => $request->rating,
                    'keterangan' => $request->keterangan,
                ]);

                return redirect()->route('admin.performance-rating.index')
                    ->with('success', 'Performance rating berhasil ditambahkan.');
            } catch (QueryException $exception) {
                if (! $this->isDuplicateKodeRatingException($exception)) {
                    throw $exception;
                }
            }
        }

        return back()
            ->withInput()
            ->withErrors(['rating' => 'Gagal membuat kode rating unik. Silakan coba lagi.']);
    }

    public function edit(PerformanceRating $performanceRating)
    {
        return view('admin.performance_rating.edit', compact('performanceRating'));
    }

    public function update(Request $request, PerformanceRating $performanceRating)
    {
        $request->validate([
            'rating' => 'required|string|max:100',
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

    private function generateNextKodeRating(): string
    {
        $nextNumber = ((int) PerformanceRating::query()
            ->selectRaw("COALESCE(MAX(CAST(SUBSTRING_INDEX(kode_rating, '-', -1) AS UNSIGNED)), 0) as max_num")
            ->value('max_num')) + 1;

        do {
            $kode = 'RTG-'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (PerformanceRating::where('kode_rating', $kode)->exists());

        return $kode;
    }

    private function isDuplicateKodeRatingException(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'duplicate entry')
            && (str_contains($message, 'kode_rating') || str_contains($message, 'performance_rating_kode_rating_unique'));
    }
}
