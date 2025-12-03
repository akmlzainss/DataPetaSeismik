@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-8 max-w-6xl">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Hubungi Balai Besar</h1>
        <p class="text-xl text-gray-600 mb-12">Sampaikan pertanyaan, permintaan data, atau masukan Anda melalui formulir berikut atau informasi kontak di samping.</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            {{-- Informasi Kontak --}}
            <div class="lg:col-span-1 space-y-8 p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 border-b pb-2 mb-4">Detail Kontak</h2>
                
                {{-- Alamat --}}
                <div class="flex items-start space-x-4">
                    <i class="fas fa-map-marker-alt text-2xl text-blue-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-xl text-gray-800">Alamat Kantor</h3>
                        <p class="text-gray-600">Jl. Diponegoro No.57<br>Bandung 40122, Indonesia</p>
                    </div>
                </div>
                
                {{-- Telepon --}}
                <div class="flex items-start space-x-4">
                    <i class="fas fa-phone-alt text-2xl text-blue-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-xl text-gray-800">Telepon/Fax</h3>
                        <p class="text-gray-600">(022) 7272606</p>
                    </div>
                </div>
                
                {{-- Email --}}
                <div class="flex items-start space-x-4">
                    <i class="fas fa-envelope text-2xl text-blue-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-xl text-gray-800">Email Resmi</h3>
                        <p class="text-gray-600">info@bbspgl.esdm.go.id</p>
                    </div>
                </div>
                
                {{-- Jam Operasional --}}
                <div class="flex items-start space-x-4">
                    <i class="fas fa-clock text-2xl text-blue-600 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-xl text-gray-800">Jam Layanan</h3>
                        <p class="text-gray-600">Senin - Jumat, 08.00 - 16.00 WIB</p>
                    </div>
                </div>
            </div>

            {{-- Formulir Kontak --}}
            <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-xl border border-gray-200">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Kirim Pesan</h2>
                {{-- Sesuaikan action dengan route POST form kontak Anda --}}
                <form action="#" method="POST" class="space-y-6"> 
                    @csrf 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Anda</label>
                            <input type="email" id="email" name="email" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="subjek" class="block text-sm font-medium text-gray-700 mb-1">Subjek Pesan</label>
                        <input type="text" id="subjek" name="subjek" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="pesan" class="block text-sm font-medium text-gray-700 mb-1">Pesan Anda</label>
                        <textarea id="pesan" name="pesan" rows="6" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-150 shadow-md">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection