<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin BBSPGL')</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    @stack('styles')
</head>
<body>
    @include('layouts.sidebar')

    <main class="main-content">
        @yield('content')
    </main>

    @stack('scripts')
    <title>@yield('title', 'BBSPGL - Balai Besar Survei dan Pemetaan Geologi Kelautan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @yield('styles')
    <style>
        main { padding-top: 4.5rem; }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

<!-- NAVBAR KUNING EMAS - UKURAN LEBIH KECIL -->
<header class="bg-[#ffd700] shadow-lg fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('beranda') }}">
                    <img src="{{ asset('images/fix.png') }}" alt="Logo BBSPGL" class="h-12 drop-shadow-md">
                </a>
            </div>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ route('beranda') }}"
                   class="px-4 py-2 text-[#003366] font-normal text-base rounded-lg transition-all duration-300
                   {{ request()->routeIs('beranda') 
                       ? 'bg-white/20 border-2 border-white shadow-md' 
                       : 'hover:bg-white/10 hover:border-white/30 border-2 border-transparent' }}">
                    Beranda
                </a>
                <a href="{{ route('peta') }}"
                   class="px-4 py-2 text-[#003366] font-normal text-base rounded-lg transition-all duration-300
                   {{ request()->routeIs('peta') 
                       ? 'bg-white/20 border-2 border-white shadow-md' 
                       : 'hover:bg-white/10 hover:border-white/30 border-2 border-transparent' }}">
                    Peta
                </a>
                <a href="{{ route('katalog') }}"
                   class="px-4 py-2 text-[#003366] font-normal text-base rounded-lg transition-all duration-300
                   {{ request()->routeIs('katalog') 
                       ? 'bg-white/20 border-2 border-white shadow-md' 
                       : 'hover:bg-white/10 hover:border-white/30 border-2 border-transparent' }}">
                    Katalog
                </a>
            </nav>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobileMenuBtn" class="text-[#003366]">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-[#ffd700] border-t-2 border-[#003366]">
        <div class="px-4 py-4 space-y-3 text-center">
            <a href="{{ route('beranda') }}"
               class="block px-6 py-3 text-[#003366] font-normal text-lg rounded-lg transition-all
               {{ request()->routeIs('beranda') 
                   ? 'bg-white/25 border-2 border-white shadow-lg' 
                   : 'border-2 border-white/50' }}">
                Beranda
            </a>
            <a href="{{ route('peta') }}"
               class="block px-6 py-3 text-[#003366] font-normal text-lg rounded-lg transition-all
               {{ request()->routeIs('peta') 
                   ? 'bg-white/25 border-2 border-white shadow-lg' 
                   : 'border-2 border-white/50' }}">
                Peta
            </a>
            <a href="{{ route('katalog') }}"
               class="block px-6 py-3 text-[#003366] font-normal text-lg rounded-lg transition-all
               {{ request()->routeIs('katalog') 
                   ? 'bg-white/25 border-2 border-white shadow-lg' 
                   : 'border-2 border-white/50' }}">
                Katalog
            </a>
        </div>
    </div>
</header>

<!-- Konten Utama -->
<main class="flex-1">
    @yield('content')
</main>

<!-- Footer Rapi 3 Kolom -->
<footer class="bg-[#002855] text-white py-16">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-sm">

            <!-- Kolom 1: Logo + Deskripsi + Sosmed + Hak Cipta -->
            <div class="flex flex-col items-center lg:items-start">
                <img src="{{ asset('images/pict.png') }}" alt="Logo Badan Geologi" class="h-24 mb-6">

                <p class="text-xs leading-relaxed text-gray-300 text-center lg:text-left mb-8">
                    Berdasarkan Peraturan Presiden RI No 97 th 2021, Badan Geologi mempunyai tugas menyelenggarakan penyelidikan dan pelayanan di bidang sumber daya geologi, vulkanologi dan mitigasi bencana geologi, air tanah, dan geologi lingkungan, serta survei geologi.
                </p>

                <div class="flex space-x-6 mb-8">
                    <a href="https://www.facebook.com/p/Badan-Geologi-100068349101047/" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-facebook-f"></i></a>
                    <a href="http://x.com/badangeologi_" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/badan.geologi/" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.youtube.com/c/BadanGeologiBG" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.tiktok.com/@badangeologi" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-tiktok"></i></a>
                </div>

                <p class="text-xs text-gray-400 text-center lg:text-left">
                    Hak Cipta © {{ date('Y') }} Badan Geologi,<br>
                    Kementerian Energi dan Sumber Daya Mineral
                </p>
            </div>

            <!-- Kolom 2: Berita Terkini -->
            <div class="flex flex-col">
                <h3 class="text-2xl font-bold text-[#FDB813] mb-8">Berita Terkini</h3>
                <ul class="space-y-6">
                    <li>
                        <a href="https://geologi.esdm.go.id/media-center/potensi-energi-laut-indonesia-survei-dan-pemetaan-potensi-energi-arus-laut" class="text-gray-200 hover:text-[#FDB813] transition text-base leading-tight">
                            Potensi Energi Laut Indonesia : Survei Dan Pemetaan Potensi Energi Arus Laut
                        </a>
                        <p class="text-xs text-yellow-400 mt-2">Selasa, 23 September 2025</p>
                    </li>
                    <li>
                        <a href="https://geologi.esdm.go.id/media-center/potensi-energi-laut-indonesia" class="text-gray-200 hover:text-[#FDB813] transition text-base leading-tight">
                            Potensi Energi Laut Indonesia
                        </a>
                        <p class="text-xs text-yellow-400 mt-2">Rabu, 13 Agustus 2025</p>
                    </li>
                    <li>
                        <a href="https://geologi.esdm.go.id/media-center/sinergi-bbspgl-psg-dan-phe-mendukung-eksplorasi-hidrogen-alami" class="text-gray-200 hover:text-[#FDB813] transition text-base leading-tight">
                            Sinergi Bbspgl, Psg, Dan Phe Mendukung Eksplorasi Hidrogen Alami
                        </a>
                        <p class="text-xs text-yellow-400 mt-2">Rabu, 9 Juli 2025</p>
                    </li>
                </ul>
            </div>

            <!-- Kolom 3: Jam Kerja -->
            <div class="flex flex-col">
                <h3 class="text-2xl font-bold text-[#FDB813] mb-8">Jam Kerja</h3>
                <p class="text-gray-300 mb-8 text-lg">Kami siap melayani Anda.</p>
                <ul class="space-y-4 text-gray-200 text-base">
                    <li class="flex justify-between">
                        <span>Senin–Kamis</span>
                        <span class="font-medium">08:00–16:00 WIB</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Jumat</span>
                        <span class="font-medium">08:00–16:30 WIB</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sabtu & Minggu</span>
                        <span class="text-red-400 font-medium">Tutup</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Tanggal Merah</span>
                        <span class="text-red-400 font-medium">Tutup</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright bawah -->
        <div class="border-t border-gray-700 mt-12 pt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} BBSPGL - Balai Besar Survei dan Pemetaan Geologi Kelautan. All rights reserved.
        </div>
    </div>
</footer>

@yield('scripts')

<script>
    document.getElementById('mobileMenuBtn')?.addEventListener('click', function () {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
</script>
</body>
</html>