<x-app-layout>
    <h1 class="text-4xl font-bold text-[#7FB3D5] mb-20">Input Order</h1>

    <div class="max-w-xl mx-auto mt-20">
        <label class="block text-black font-semibold mb-2 text-center md:text-left">
            Masukkan Nomor WhatsApp
        </label>
        
        <form action="#" method="POST" class="flex flex-col items-end">
            @csrf
            <input type="text" 
                   placeholder="No HP" 
                   class="w-full p-4 bg-gray-200 border-none rounded-xl focus:ring-2 focus:ring-blue-400 mb-4"
            >
            
            <button type="submit" 
                    class="bg-[#3b66ff] text-white px-10 py-2 rounded-xl font-semibold shadow-lg hover:bg-blue-700 transition"
            >
                Cari
            </button>
        </form>
    </div>
</x-app-layout>