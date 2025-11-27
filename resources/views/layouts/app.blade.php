<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BBSPGL - Balai Besar Survei dan Pemetaan Geologi Kelautan')</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        main { padding-top: 4.5rem; }
    </style>
</head>

<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

<!-- NAVBAR -->
<header class="bg-[#ffd700] shadow-lg fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">

            <div class="flex items-center">
                <a href="{{ route('beranda') }}">
                    <img src="{{ asset('images/fix.png') }}" alt="Logo BBSPGL" class="h-12 drop-shadow-md">
                </a>
            </div>

            <!-- Menu Desktop -->
            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ route('beranda') }}"
                   class="px-4 py-2 text-[#003366] font-normal text-base rounded-lg transition-all duration-300
                   {{ request()->routeIs('beranda') ? 'bg-white/20 border-2 border-white shadow-md' : 'hover:bg-white/10 hover:border-white/30 border-2 border-transparent' }}">
                    Beranda
                </a>

                <a href="{{ route('peta') }}"
                   class="px-4 py-2 text-[#003366] font-normal text-base rounded-lg transition-all duration-300
                   {{ request()->routeIs('peta') ? 'bg-white/20 border-2 border-white shadow-md' : 'hover:bg-white/10 hover:border-white/30 border-2 border-transparent' }}">
                    Peta
                </a>

                <a href="{{ route('katalog') }}"
                   class="px-4 py-2 text-[#003366] font-normal text-base rounded-lg transition-all duration-300
                   {{ request()->routeIs('katalog') ? 'bg-white/20 border-2 border-white shadow-md' : 'hover:bg-white/10 hover:border-white/30 border-2 border-transparent' }}">
                    Katalog
                </a>
            </nav>

            <!-- Mobile Menu -->
            <div class="md:hidden">
                <button id="mobileMenuBtn" class="text-[#003366]">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div id="mobileMenu" class="hidden md:hidden bg-[#ffd700] border-t-2 border-[#003366]">
        <div class="px-4 py-4 space-y-3 text-center">
            <a href="{{ route('beranda') }}"
               class="block px-6 py-3 text-[#003366] font-normal text-lg rounded-lg transition-all
               {{ request()->routeIs('beranda') ? 'bg-white/25 border-2 border-white shadow-lg' : 'border-2 border-white/50' }}">
                Beranda
            </a>

            <a href="{{ route('peta') }}"
               class="block px-6 py-3 text-[#003366] font-normal text-lg rounded-lg transition-all
               {{ request()->routeIs('peta') ? 'bg-white/25 border-2 border-white shadow-lg' : 'border-2 border-white/50' }}">
                Peta
            </a>

            <a href="{{ route('katalog') }}"
               class="block px-6 py-3 text-[#003366] font-normal text-lg rounded-lg transition-all
               {{ request()->routeIs('katalog') ? 'bg-white/25 border-2 border-white shadow-lg' : 'border-2 border-white/50' }}">
                Katalog
            </a>
        </div>
    </div>
</header>

<!-- CONTENT -->
<main class="flex-1">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="bg-[#002855] text-white py-16">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-sm">

            <!-- Kolom 1 -->
            <div class="flex flex-col items-center lg:items-start">
                <img src="{{ asset('images/pict.png') }}" class="h-24 mb-6">

                <p class="text-xs text-gray-300 text-center lg:text-left mb-8">
                    Berdasarkan Peraturan Presiden RI No 97 th 2021, Badan Geologi mempunyai tugas ...
                </p>

                <div class="flex space-x-6 mb-8">
                    <a href="#" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white hover:text-[#FDB813] transition text-xl"><i class="fab fa-instagram"></i></a>
                </div>

                <p class="text-xs text-gray-400">
                    Hak Cipta © {{ date('Y') }} Badan Geologi
                </p>
            </div>

            <!-- Kolom 2 -->
            <div>
                <h3 class="text-2xl font-bold text-[#FDB813] mb-8">Berita Terkini</h3>
                <ul class="space-y-6">
                    <li>
                        <a href="#" class="text-gray-200 hover:text-[#FDB813]">Potensi Energi Laut Indonesia</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-200 hover:text-[#FDB813]">Survei dan Pemetaan</a>
                    </li>
                </ul>
            </div>

            <!-- Kolom 3 -->
            <div>
                <h3 class="text-2xl font-bold text-[#FDB813] mb-8">Jam Kerja</h3>
                <ul class="space-y-4 text-gray-200 text-base">
                    <li class="flex justify-between">
                        <span>Senin–Kamis</span>
                        <span>08:00–16:00 WIB</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Jumat</span>
                        <span>08:00–16:30 WIB</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center text-xs text-gray-500 mt-12">
        © {{ date('Y') }} BBSPGL - All rights reserved.
    </div>
</footer>

<!-- SCRIPT -->
<script>
    document.getElementById('mobileMenuBtn')?.addEventListener('click', () =>
        document.getElementById('mobileMenu').classList.toggle('hidden')
    );
</script>

@stack('scripts')
</body>
</html>
