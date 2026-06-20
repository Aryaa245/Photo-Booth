<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SnapStudio') }} - @yield('title', 'Photobooth Premium')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Tailwind CDN (untuk development) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'obsidian': '#0D0D0D',
                        'cream': '#F5F0E8',
                        'gold': '#C9A96E',
                        'gold-light': '#E8D5B0',
                        'charcoal': '#2A2A2A',
                        'film': '#1A1A1A',
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'Georgia', 'serif'],
                        'body': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }
        body { background-color: #0D0D0D; color: #F5F0E8; }

        /* Film Strip Animation */
        .film-strip {
            animation: slideFilm 20s linear infinite;
        }
        @keyframes slideFilm {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Gold shimmer */
        .gold-shimmer {
            background: linear-gradient(90deg, #C9A96E, #E8D5B0, #C9A96E);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease-in-out infinite;
        }
        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Scrollbar custom */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #0D0D0D; }
        ::-webkit-scrollbar-thumb { background: #C9A96E; border-radius: 2px; }

        /* Camera shutter animation */
        .shutter-btn:active { transform: scale(0.95); }

        /* Photo frame hover */
        .photo-frame {
            transition: all 0.3s ease;
            border: 1px solid #2A2A2A;
        }
        .photo-frame:hover {
            border-color: #C9A96E;
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(201, 169, 110, 0.15);
        }
    </style>

    @stack('styles')
</head>
<body class="font-body antialiased">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-obsidian/90 backdrop-blur-md border-b border-charcoal">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 border-2 border-gold flex items-center justify-center">
                    <div class="w-3 h-3 bg-gold rounded-full"></div>
                </div>
                <span class="font-display text-xl font-semibold text-cream">Snap<span class="text-gold">Studio</span></span>
            </a>

            {{-- Navigation --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-sm text-cream/70 hover:text-gold transition-colors">Beranda</a>
                <a href="{{ route('gallery') }}" class="text-sm text-cream/70 hover:text-gold transition-colors">Galeri</a>
                <a href="{{ route('photobooth') }}" class="text-sm text-cream/70 hover:text-gold transition-colors">Photobooth</a>
                <a href="{{ route('home') }}#pricing" class="text-sm text-cream/70 hover:text-gold transition-colors">Paket</a>
            </div>

            {{-- CTA --}}
            <a href="{{ route('photobooth') }}" class="bg-gold text-obsidian text-sm font-semibold px-5 py-2.5 hover:bg-gold-light transition-colors">
                Mulai Sesi
            </a>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-film border-t border-charcoal mt-24">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-7 h-7 border-2 border-gold flex items-center justify-center">
                            <div class="w-2.5 h-2.5 bg-gold rounded-full"></div>
                        </div>
                        <span class="font-display text-lg font-semibold text-cream">Snap<span class="text-gold">Studio</span></span>
                    </div>
                    <p class="text-cream/50 text-sm leading-relaxed">
                        Studio photobooth premium untuk kenangan yang tak terlupakan. Setiap foto adalah cerita.
                    </p>
                </div>
                <div>
                    <h4 class="text-cream/80 text-sm font-semibold mb-4 uppercase tracking-widest">Tautan</h4>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('home') }}" class="text-cream/50 text-sm hover:text-gold transition-colors">Beranda</a>
                        <a href="{{ route('gallery') }}" class="text-cream/50 text-sm hover:text-gold transition-colors">Galeri</a>
                        <a href="{{ route('photobooth') }}" class="text-cream/50 text-sm hover:text-gold transition-colors">Photobooth</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-cream/80 text-sm font-semibold mb-4 uppercase tracking-widest">Kontak</h4>
                    <div class="flex flex-col gap-2">
                        <span class="text-cream/50 text-sm">snapstudio@email.com</span>
                        <span class="text-cream/50 text-sm">+62 812 3456 7890</span>
                        <span class="text-cream/50 text-sm">Semarang, Jawa Tengah</span>
                    </div>
                </div>
            </div>
            <div class="border-t border-charcoal mt-10 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-cream/30 text-xs">© {{ date('Y') }} SnapStudio. Semua hak dilindungi.</p>
                <p class="text-cream/30 text-xs">Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
