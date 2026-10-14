<?php

namespace App\Http\Controllers;

use App\Models\events;
use App\Models\order;
use App\Models\order_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {         // Mengambil semua data events dari database
        $events = events::all();
        // Mengirim data events ke view customer/index.blade.php
        return view('customer.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {         // Validasi input data dari form pesanan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|string|min:1',
            'items' => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);
        // Menyaring hanya item menu yang kuantitas pesanannya lebih dari 0
        $orderedItems = array_filter($request->items, fn ($qty) => $qty > 0);
        // Jika tidak ada item yang dipilih, kembalikan dengan pesan error
        if (empty($orderedItems)) {
            return back()->with('error', 'Pilih minimal 1 tipe konser!');
        }
        // Memulai transaksi multi-tabel untuk menjaga konsistensi database
        DB::beginTransaction();
        try {             // Membuat data transaksi utama di tabel orders
            $orders = order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_price' => 0,
                'status' => 'Pending',
            ]);

            $totalPrice = 0;
            // Melakukan looping untuk setiap item yang dipesan dan menyimpannya ke tabel order_details
            foreach ($orderedItems as $eventsId => $quantity) {
                $events = events::findOrFail($eventsId);
                $subtotal = $events->ticket_price * $quantity;
                $totalPrice += $subtotal;

                order_details::create([
                    'order_id' => $orders->id,
                    'event_id' => $events->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }
            // Memperbarui total harga keseluruhan pesanan di tabel orders
            $orders->update(['total_price' => $totalPrice]);
            // Melakukan commit transaksi jika semua proses berhasil
            DB::commit();

            return redirect()->route('customer.index')->with('success', 'Pesanan berhasil dibuat! Email: ' . $events->customer_email);
        } catch (\Exception $e) {
            DB::rollBack();             // Rollback semua perubahan database jika terjadi kesalahan
            return back()->with('error', 'Gagal mmeproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }

    //Menampilkan rekap seluruh pesanan yang masuk pada dashboard sisi Admin
    public function adminDashboard()
    {         // Mengambil seluruh pesanan beserta detail item dan eventnya
        $orders = order::With('order_details.events')->latest()->get();
        // Mengirim data pesanan ke halaman dashboard admin
        return view('dashboard', compact('orders'));
    }

    //Memperbarui status pesanan (Pending, Confirmed, Cancelled) oleh Admin.
    public function updateStatus(Request $request, $id)
    {         // Validasi status yang dikirimkan
         $request->validate(['status' => 'required|string']);
        // Mencari pesanan berdasarkan ID dan memperbarui statusnya
         $orders = order::findOrFail($id);
         $orders->update(['status' => $request->status]);

         return back()->with('success', 'status pesanan ticket #' . $orders->id . 'berhasil diperbarui' );
    }

}


