<?php

// Menentukan namespace lokasi controller ini berada di dalam struktur folder Laravel

namespace App\Http\Controllers;


use App\Models\events; // Mengimpor Model Food untuk berinteraksi dengan tabel 'foods' di database
use Illuminate\Http\Request; // Mengimpor Request class untuk menangkap data yang dikirim dari form HTTP
use Illuminate\Support\Facades\Storage; // Mengimpor Facade Storage untuk manajemen file (simpan dan hapus gambar di server/storage)

// Mendefinisikan class FoodController yang mewarisi class dasar Controller
class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */     // Method untuk menampilkan daftar seluruh data event (halaman index)
    public function index()
    {
        $events = events::latest()->paginate(10);         // Mengambil data event dari database: diurutkan dari yang terbaru (latest) dan dibagi 10 data per halaman (paginate)

        return view('admin.events.index', compact('events'));         // Mengembalikan tampilan view 'admin/eventsw/index.blade.php' sambil mengirimkan variabel $events menggunakan compact()
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');         // Merender tampilan form tambah event pada view 'admin/events/create.blade.php'
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {         // Memvalidasi data input dari form sebelum dimasukkan ke database
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Musik,Festival,Seminar',
            'ticket_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

         // Inisialisasi variabel path gambar dengan null jika user tidak mengunggah gambar
        $imagePath = null;
        // Memeriksa apakah ada file gambar yang diunggah dari input form 'image'
        if ($request->hasFile('image')) {
            // Menyimpan file gambar ke folder 'foods' di dalam disk 'public' (storage/app/public/events) dan menghasilkan nama file acak yang unik
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // Menyimpan data events baru ke dalam tabel 'events' di database menggunakan Mass Assignment Eloquent
        events::create([
            'title' => $request->title,
            'category' => $request->category,
            'ticket_price' => $request->ticket_price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        // Mengarahkan kembali user ke halaman rute 'events.index' dengan pesan notifikasi sukses di session
        return redirect()->route('events.index')->with('success', 'Data konser berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(events $events)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(events $events)     // Method untuk menampilkan form edit data makanan berdasarkan ID makanan (Route Model Binding)
    {
        return view ('admin.events.edit', compact('events'));         // Merender view 'admin/events/edit.blade.php' dan membawa objek model $food yang akan diedit

    }

    /**
     * Update the specified resource in storage.
     */ // Method untuk memproses pembaruan data events yang telah diedit (HTTP PUT/PATCH)
    public function update(Request $request, events $events)
    { // Validasi input data yang diubah untuk memastikan integritas dan keamanan data
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Musik,Festival,Seminar',
            'ticket_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Default path gambar tetap menggunakan path gambar lama dari database
        $imagePath = $events->image;
        // Jika ada file gambar baru yang diunggah oleh user
        if ($request->hasFile('image')) {
            // Periksa apakah gambar lama ada di disk 'public', jika ada maka hapus file gambar lama tersebut agar tidak menumpuk di storage
            if ($events->image && Storage::disk('public')->exists($events->image)){
                Storage::disk('public')->delete($events->image);
            }
            // Simpan file gambar baru ke folder 'events' pada disk 'public'
            $imagePath = $request->file('image')->store('events', 'public');
        }

         // Memperbarui record events di database dengan nilai-nilai baru
        $events->update([
            'title' => $request->title,
            'category' => $request->category,
            'ticket_price' => $request->ticket_price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        // Mengarahkan kembali ke daftar events dengan pesan notifikasi berhasil diperbarui
        return redirect()->route('events.index')->with('success', 'Data konser berhasil diperbarui');

    }

    /**
     * Remove the specified resource from storage.
     */     // Method untuk menghapus data event dari database (HTTP DELETE)
    public function destroy(events $events)
    {         // Periksa apakah record event memiliki file gambar fisik di storage, jika ada hapus filenya
        if ($events->image && Storage::disk('public')->exists($events->image)){
            Storage::disk('public')->delete($events->image);
        }

        // Menghapus data baris record event dari tabel di database
        $events->delete();
        // Mengarahkan kembali ke halaman index makanan dengan pesan notifikasi berhasil dihapus
        return redirect()->route('events.index')->with('success', 'Data konser berhasil dihapus');
    }
}

