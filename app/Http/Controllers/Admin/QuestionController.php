<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::where('is_default', true)
            ->orWhereNull('user_id')
            ->latest()
            ->paginate(12);

        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_sampah' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:organik,anorganik,b3'],
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'fakta_edukasi' => ['nullable', 'string'],
        ]);

        $data = [
            'nama_sampah' => $request->nama_sampah,
            'kategori' => $request->kategori,
            'fakta_edukasi' => $request->fakta_edukasi,
            'is_default' => true,
            'user_id' => null,
        ];

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Buat direktori jika belum ada
            if (!File::exists(public_path('images/sampah'))) {
                File::makeDirectory(public_path('images/sampah'), 0755, true);
            }

            $file->move(public_path('images/sampah'), $filename);
            $data['gambar'] = 'images/sampah/' . $filename;
        }

        Question::create($data);

        return redirect()->route('admin.questions.index')->with('success', 'Soal default berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'nama_sampah' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:organik,anorganik,b3'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'fakta_edukasi' => ['nullable', 'string'],
        ]);

        $data = [
            'nama_sampah' => $request->nama_sampah,
            'kategori' => $request->kategori,
            'fakta_edukasi' => $request->fakta_edukasi,
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada dan bukan bawaan seeder asli (kita hanya hapus jika file fisik ada)
            if ($question->gambar && File::exists(public_path($question->gambar)) && !str_contains($question->gambar, 'seeder_placeholder')) {
                // (Optional: hapus file lama untuk menghemat storage)
                // File::delete(public_path($question->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/sampah'), $filename);
            $data['gambar'] = 'images/sampah/' . $filename;
        }

        $question->update($data);

        return redirect()->route('admin.questions.index')->with('success', 'Soal default berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        // Hapus gambar jika ada
        if ($question->gambar && File::exists(public_path($question->gambar))) {
            // File::delete(public_path($question->gambar));
        }

        $question->delete();

        return redirect()->route('admin.questions.index')->with('success', 'Soal default berhasil dihapus.');
    }
}
