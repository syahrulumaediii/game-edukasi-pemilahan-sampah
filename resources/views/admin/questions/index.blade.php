<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
                {{ __('Master Bank Soal Default') }}
            </h2>
            <a href="{{ route('admin.questions.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150 flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Soal Default
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg text-emerald-800 shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Grid Card Soal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @forelse($questions as $question)
                    <div class="bg-white rounded-3xl p-5 shadow-md border border-gray-100 flex flex-col justify-between transform hover:-translate-y-1 transition-transform duration-200">
                        <div>
                            <!-- Thumbnail Gambar -->
                            <div class="w-full aspect-square bg-gray-55 rounded-2xl mb-4 flex items-center justify-center overflow-hidden border border-gray-50 relative group">
                                @if($question->gambar)
                                    <img src="{{ asset($question->gambar) }}" alt="{{ $question->nama_sampah }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" onerror="this.onerror=null; this.src='https://placehold.co/150?text={{ urlencode($question->nama_sampah) }}';">
                                @else
                                    <div class="text-4xl">🗑️</div>
                                @endif
                                
                                <!-- Kategori Badge Floating -->
                                <div class="absolute top-3 left-3">
                                    @if($question->kategori === 'organik')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-emerald-800 bg-emerald-100 rounded-full">Organik</span>
                                    @elseif($question->kategori === 'anorganik')
                                        <span class="px-2.5 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Anorganik</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">B3</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Nama Sampah -->
                            <h4 class="font-fredoka font-bold text-lg text-gray-800 mb-1.5">{{ $question->nama_sampah }}</h4>
                            
                            <!-- Fakta Edukasi Singkat -->
                            <p class="text-xs text-gray-400 line-clamp-3 mb-4">
                                {{ $question->fakta_edukasi ?? 'Belum ada fakta edukasi singkat.' }}
                            </p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                            <span class="text-[10px] text-gray-300 font-semibold uppercase tracking-wider">Master Default</span>
                            <div class="flex space-x-1.5">
                                <a href="{{ route('admin.questions.edit', $question) }}" class="p-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg transition duration-150" title="Edit Soal">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal default ini dari bank soal global?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition duration-150" title="Hapus Soal">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-3xl p-8 border border-gray-100">
                        <span class="text-4xl block mb-2">🗑️</span>
                        Belum ada soal default terdaftar. Klik tombol Tambah untuk membuat soal pertama.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
