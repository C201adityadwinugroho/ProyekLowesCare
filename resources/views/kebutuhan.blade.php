<x-app-layout>
    <h1 class="text-4xl font-bold text-[#7FB3D5] mb-12">Kebutuhan</h1>

    <form action="#" method="POST" class="max-w-5xl">
        @csrf
        
        <div class="space-y-4 mb-10">
            @for ($i = 1; $i <= 7; $i++)
            <div class="flex gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="nama_kebutuhan[]" 
                           placeholder="Nama Kebutuhan" 
                           class="w-full p-4 bg-gray-200 border-none rounded-xl text-gray-600 focus:ring-2 focus:ring-blue-400"
                    >
                </div>
                
                <div class="w-32">
                    <div class="bg-gray-200 rounded-xl p-2 px-4">
                        <label class="block text-[10px] text-gray-500 uppercase font-bold leading-tight">Stok</label>
                        <input type="text" 
                               name="stok[]" 
                               placeholder="Terakhir" 
                               class="w-full bg-transparent border-none p-0 text-sm focus:ring-0 text-gray-700"
                        >
                    </div>
                </div>
            </div>
            @endfor
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-[#3b66ff] text-white px-12 py-3 rounded-xl font-semibold shadow-lg hover:bg-blue-700 transition"
            >
                Kirim
            </button>
        </div>
    </form>
</x-app-layout>