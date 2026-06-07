<?php

namespace App\Http\Controllers;

use App\Models\RewardPunishment;
use Illuminate\Http\Request;

class RewardPunishmentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q'));
        $filterTipe = $request->input('tipe');

        $items = RewardPunishment::when($search !== '', function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('grade', 'like', "%{$search}%");
            });
        })
        ->when($filterTipe, fn($q) => $q->where('tipe', $filterTipe))
        ->orderBy('tipe')
        ->orderBy('grade');

        $items = $this->paginateWithPerPage($items, $request, 10);

        return view('admin.reward_punishment.index', compact('items', 'search', 'filterTipe'));
    }

    public function create()
    {
        return view('admin.reward_punishment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'      => 'required|string|max:20|unique:reward_punishment,kode',
            'tipe'      => 'required|in:reward,punishment',
            'grade'     => 'required|in:A,B,C,D,E',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan'    => 'nullable|string|max:50',
            'jumlah'    => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        RewardPunishment::create([
            'kode'      => $request->kode,
            'tipe'      => $request->tipe,
            'grade'     => $request->grade,
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'satuan'    => $request->satuan,
            'jumlah'    => $request->jumlah,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.reward-punishment.index')
            ->with('success', 'Data reward/punishment berhasil ditambahkan.');
    }

    public function edit(RewardPunishment $rewardPunishment)
    {
        return view('admin.reward_punishment.edit', compact('rewardPunishment'));
    }

    public function update(Request $request, RewardPunishment $rewardPunishment)
    {
        $request->validate([
            'kode'      => 'required|string|max:20|unique:reward_punishment,kode,' . $rewardPunishment->id,
            'tipe'      => 'required|in:reward,punishment',
            'grade'     => 'required|in:A,B,C,D,E',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'satuan'    => 'nullable|string|max:50',
            'jumlah'    => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $rewardPunishment->update([
            'kode'      => $request->kode,
            'tipe'      => $request->tipe,
            'grade'     => $request->grade,
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
            'satuan'    => $request->satuan,
            'jumlah'    => $request->jumlah,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.reward-punishment.index')
            ->with('success', 'Data reward/punishment berhasil diperbarui.');
    }

    public function destroy(RewardPunishment $rewardPunishment)
    {
        $rewardPunishment->delete();

        return redirect()->route('admin.reward-punishment.index')
            ->with('success', 'Data reward/punishment berhasil dihapus.');
    }
}
