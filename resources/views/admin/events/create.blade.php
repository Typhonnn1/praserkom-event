{{-- // Menggunakan template layout utama aplikasi (layouts/app.blade.php) bawaan Laravel Breeze --}}
<x-app-layout>
    {{-- // Slot header untuk judul navbar atas halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Events</h2>
    </x-slot>

    {{-- // Wrapper container dengan lebar maksimal 2xl agar form tampak rapi dan berada di tengah --}}
    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        {{-- // Form kirim data ke route('events.store') dengan method POST. enctype="multipart/form-data" WAJIB ada untuk mengunggah file gambar --}}
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm border">
            {{-- // Token CSRF wajib di Laravel untuk melindungi request form dari serangan pemalsuan request lintas situs --}}
            @csrf

            {{-- // Input grup: Nama Makanan --}}
            <div class="mb-4">
                {{-- // Label teks untuk field nama makanan --}}
                <label class="block font-medium mb-1">Nama Event</label>
                <input type="text" name="title" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Kategori</label>
                {{-- // Dropdown select untuk menentukan kategori menu yang dipilih --}}
                <select name="category" class="w-full border rounded p-2" required>
                    <option value="Musik">Musik</option>
                    <option value="Festival">Festival</option>
                    <option value="Seminar">Seminar</option>
                </select>
            </div>

            {{-- // Input grup: Harga (Rp) --}}
            <div class="mb-4">
                {{-- // Label teks untuk field input nominal harga --}}
                <label class="block font-medium mb-1">Harga (Rp)</label>
                {{-- // Input bertipe angka (number) untuk harga dalam mata uang rupiah --}}
                <input type="number" name="ticket_price" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                {{-- // Label teks untuk field penjelasan atau deskripsi makanan --}}
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border rounded p-2" rows="3" required></textarea>
            </div>

            {{-- // Input grup: Upload File Gambar --}}
            <div class="mb-4">
                {{-- // Label teks untuk upload file foto makanan --}}
                <label class="block font-medium mb-1">Gambar Event</label>
                <input type="file" name="image" class="w-full border rounded p-2">
            </div>

            {{-- // Tombol submit untuk mengeksekusi pengiriman data form ke FoodController@store via HTTP POST --}}
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded font-semibold">Simpan Data</button>
        </form>
    </div>
</x-app-layout>


