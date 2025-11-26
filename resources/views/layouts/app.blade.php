<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BBSPGL - Balai Besar Survei dan Pemetaan Geologi Kelautan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="bg-white shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo + Nama Instansi -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BBSPGL" class="h-12">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Kementerian ESDM</h1>
                        <p class="text-xs text-gray-600">Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL)</p>
                    </div>
                </div>

                <!-- Menu Navigasi -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('beranda') }}"
                       class="px-4 py-2 rounded-lg text-gray-700 font-medium hover:bg-blue-600 hover:text-white transition {{ request()->routeIs('beranda') ? 'bg-blue-600 text-white' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('peta') }}"
                       class="px-4 py-2 rounded-lg text-gray-700 font-medium hover:bg-blue-600 hover:text-white transition {{ request()->routeIs('peta') ? 'bg-blue-600 text-white' : '' }}">
                        Peta
                    </a>
                    <a href="{{ route('katalog') }}"
                       class="px-4 py-2 rounded-lg text-gray-700 font-medium hover:bg-blue-600 hover:text-white transition {{ request()->routeIs('katalog') ? 'bg-blue-600 text-white' : '' }}">
                        Katalog
                    </a>
                </nav>

                <!-- Mobile Menu Button (nanti bisa ditambah hamburger kalau mau) -->
                <div class="md:hidden">
                    <button class="text-gray-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-1 pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-10">
        <div class="container mx-auto px-6 text-center">
            <p>Hak Cipta © {{ date('Y') }} Balai Besar Survei dan Pemetaan Geologi Kelautan (BBSPGL)</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>