<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
                {{ __('Kelola Akun Guru') }}
            </h2>
            <a href="{{ route('admin.teachers.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150 flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Guru
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

            <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs uppercase font-semibold text-gray-400 border-b border-gray-100 pb-3">
                                <th class="py-3">Nama Lengkap</th>
                                <th class="py-3">Email</th>
                                <th class="py-3">Asal Sekolah</th>
                                <th class="py-3">Terdaftar Pada</th>
                                <th class="py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td class="py-3 font-semibold text-gray-800">{{ $teacher->name }}</td>
                                    <td class="py-3">{{ $teacher->email }}</td>
                                    <td class="py-3">
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-medium rounded-full text-xs">
                                            {{ $teacher->nama_sekolah ?? 'Belum Diatur' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-gray-500">{{ $teacher->created_at->format('d M Y') }}</td>
                                    <td class="py-3 text-right">
                                        <div class="inline-flex space-x-2">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="p-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-xl transition duration-150" title="Edit Akun">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                                </svg>
                                            </a>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun guru ini? Semua sesi game yang dibuat oleh guru ini juga akan terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition duration-150" title="Hapus Akun">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400">Belum ada akun guru terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $teachers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
