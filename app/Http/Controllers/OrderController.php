<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Pesanan.
     * Fitur Search DIBATASI: Hanya ID dan Nomor HP.
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Query (Urutkan dari yang terbaru)
        $query = Order::latest();

        // 2. LOGIKA SEARCH (HANYA ID & NO HP)
        if ($request->filled('search')) {
            $keyword = $request->search;
            
            $query->where(function($q) use ($keyword) {
                $q->where('id', $keyword)                            // Cari ID Persis
                  ->orWhere('id', 'like', "%{$keyword}%")           // Cari ID (angka mirip)
                  ->orWhere('no_hp', 'like', "%{$keyword}%")       // Cari Nomor HP
                  ->orWhere('nama_customer', 'like', "%{$keyword}%"); // <--- INI TAMBAHANNYA (Cari Nama)
                  
            });
        }

        // 3. Eksekusi Query
        $orders = $query->get();

        // 4. GROUPING
        // Wajib ada agar tampilan tabel tidak error
        $orders = $orders->groupBy(function($item) {
            return $item->created_at->format('YmdHi') . '-' . $item->no_hp;
        });

        return view('manajemen-pesanan', compact('orders'));
    }

    /**
     * Mengecek Nomor HP (Prioritas Member -> Repeat -> Baru).
     */
    public function checkNumber(Request $request)
    {
        $request->validate(['no_hp' => 'required']);
        $no_hp = $request->no_hp;

        $history = Order::where('no_hp', $no_hp)->get();
        $count = $history->count();
        $lastOrder = $history->last();
        $nama = $lastOrder ? $lastOrder->nama_customer : '';

        $isMember = $history->where('is_registered_member', 1)->first();

        if ($isMember) {
            $poin = ($count % 8) + 1; 
            return view('orders.input-order', [
                'no_hp'     => $no_hp,
                'status'    => 'Member',
                'color'     => 'text-pink-500',
                'nama'      => $nama,
                'is_member' => true, 
                'poin'      => $poin
            ]);
        } 
        elseif ($count >= 1) {
            return view('orders.input-order', [
                'no_hp'     => $no_hp,
                'status'    => 'Repeat',
                'color'     => 'text-green-600',
                'nama'      => $nama,
                'is_member' => false,
                'poin'      => 0
            ]);
        } 
        else {
            return view('orders.input-order', [
                'no_hp'     => $no_hp,
                'status'    => 'Baru',
                'color'     => 'text-blue-600',
                'nama'      => '',
                'is_member' => false,
                'poin'      => 0
            ]);
        }
    }

    /**
     * Menyimpan Data.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_hp' => 'required',
            'nama_customer' => 'required',
            'item.*' => 'required',
            'harga.*' => 'required',
        ]);

        $clickedMemberButton = ($request->action_type == 'member');
        $isExistingMember = Order::where('no_hp', $request->no_hp)
                                 ->where('is_registered_member', 1)
                                 ->exists();

        $finalIsMember = ($clickedMemberButton || $isExistingMember) ? 1 : 0;
        
        $statusText = $finalIsMember ? 'Member' : $request->tipe_customer;
        if($statusText == 'Member' && !$finalIsMember) $statusText = 'Repeat';

        $items = $request->item;

        if ($items) {
            foreach ($items as $index => $itemName) {
                if (empty($itemName)) continue;

                Order::create([
                    'no_hp'                => $request->no_hp,
                    'nama_customer'        => $request->nama_customer,
                    'tipe_customer'        => $statusText,
                    'is_registered_member' => $finalIsMember,
                    'cs'                   => $request->cs,
                    'pembayaran'           => $request->pembayaran,
                    'sumber_info'          => $request->sumber_info,
                    'status'               => 'Proses',
                    
                    'item'                 => $itemName,
                    'kategori_treatment'   => $request->kategori_treatment[$index] ?? null,
                    'tanggal_keluar'       => $request->tanggal_keluar[$index] ?? null,
                    'harga'                => $request->harga[$index] ?? 0,
                    'catatan'              => $request->catatan[$index] ?? '-',
                    'jumlah'               => 1,
                ]);
            }
        }

        return redirect()->route('pesanan.index')
            ->with('success', 'Data berhasil disimpan!');
    }
}