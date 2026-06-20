@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

{{-- HERO SECTION --}}
<section class="min-h-screen flex flex-col justify-center relative overflow-hidden">
    {{-- Background grid --}}
    <div class="absolute inset-0 opacity-5"
        style="background-image: linear-gradient(#C9A96E 1px, transparent 1px), linear-gradient(90deg, #C9A96E 1px, transparent 1px); background-size: 60px 60px;">
    </div>

    {{-- Hero Content --}}
    <div class="relative max-w-7xl mx-auto px-6 pt-12 pb-24 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        {{-- Left --}}
        <div>
            <p class="text-gold text-xs uppercase tracking-[0.3em] mb-6">Studio Photobooth Premium</p>
            <h1 class="font-display text-5xl md:text-7xl font-bold text-cream leading-tight mb-6">
                Setiap Momen<br>
                <em class="gold-shimmer not-italic">Layak Diabadikan</em>
            </h1>
            <p class="text-cream/60 text-lg leading-relaxed mb-10 max-w-md">
                Photobooth digital interaktif dengan filter premium, frame eksklusif, dan teknologi kamera terbaik untuk kenangan sempurna bersama orang-orang tersayang.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('photobooth') }}"
                   class="inline-flex items-center justify-center gap-3 bg-gold text-obsidian font-semibold px-8 py-4 hover:bg-gold-light transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Ambil Foto Sekarang
                </a>
                <a href="{{ route('gallery') }}"
                   class="inline-flex items-center justify-center gap-3 border border-charcoal text-cream/70 font-medium px-8 py-4 hover:border-gold hover:text-gold transition-all">
                    Lihat Galeri
                </a>
            </div>

            {{-- Stats --}}
            <div class="flex gap-10 mt-14 pt-10 border-t border-charcoal">
                <div>
                    <p class="font-display text-3xl font-bold text-gold">2.4K+</p>
                    <p class="text-cream/50 text-xs mt-1">Foto Diambil</p>
                </div>
                <div>
                    <p class="font-display text-3xl font-bold text-gold">150+</p>
                    <p class="text-cream/50 text-xs mt-1">Sesi Berlangsung</p>
                </div>
                <div>
                    <p class="font-display text-3xl font-bold text-gold">40+</p>
                    <p class="text-cream/50 text-xs mt-1">Frame Eksklusif</p>
                </div>
            </div>
        </div>

        {{-- Right: Film strip visual --}}
        <div class="hidden lg:block relative">
            <div class="relative w-full aspect-square max-w-sm mx-auto">
                {{-- Outer frame --}}
                <div class="absolute inset-0 border border-gold/30 rotate-3"></div>
                <div class="absolute inset-0 border border-charcoal -rotate-3"></div>

                {{-- Center camera icon --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-32 h-32 border-2 border-gold/50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-16 h-16 text-gold/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="font-display italic text-gold/50 text-sm">SnapStudio</p>
                    </div>
                </div>

                {{-- Corner marks --}}
                <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-gold"></div>
                <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-gold"></div>
                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-gold"></div>
                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-gold"></div>
            </div>
        </div>
    </div>

    {{-- Film strip scroll --}}
    <div class="absolute bottom-0 left-0 right-0 overflow-hidden h-16 flex items-center border-t border-charcoal bg-film/80">
        <div class="film-strip flex gap-0 whitespace-nowrap">
            @foreach(array_fill(0, 20, null) as $i)
            <div class="flex items-center gap-3 px-6 opacity-40">
                <div class="w-10 h-10 bg-charcoal border border-charcoal/80 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gold/50" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-gold/30 text-xs font-body tracking-wider">SNAP</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURES SECTION --}}
<section class="py-28 max-w-7xl mx-auto px-6">
    <div class="text-center mb-16">
        <p class="text-gold text-xs uppercase tracking-[0.3em] mb-4">Kenapa Memilih Kami</p>
        <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">Pengalaman Berbeda<br><em class="text-gold/80 not-italic">di Setiap Sesi</em></h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach([
            ['icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Kamera HD Premium', 'desc' => 'Resolusi tinggi untuk hasil foto tajam, jernih, dan penuh detail yang memukau.'],
            ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => '40+ Frame & Filter', 'desc' => 'Pilihan frame eksklusif dan filter artistik untuk setiap tema dan suasana hati.'],
            ['icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4', 'title' => 'Unduh Instan', 'desc' => 'Foto siap diunduh dalam hitungan detik. Simpan, bagikan, atau cetak langsung.'],
        ] as $feature)
        <div class="bg-film border border-charcoal p-8 hover:border-gold/40 transition-all group">
            <div class="w-12 h-12 border border-gold/30 flex items-center justify-center mb-6 group-hover:border-gold transition-colors">
                <svg class="w-6 h-6 text-gold/60 group-hover:text-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $feature['icon'] }}"/>
                </svg>
            </div>
            <h3 class="font-display text-xl font-semibold text-cream mb-3">{{ $feature['title'] }}</h3>
            <p class="text-cream/50 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- PRICING SECTION --}}
<section id="pricing" class="py-28 bg-film border-y border-charcoal">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-gold text-xs uppercase tracking-[0.3em] mb-4">Paket Kami</p>
            <h2 class="font-display text-4xl md:text-5xl font-bold text-cream">Pilih Paket <em class="text-gold/80 not-italic">Terbaik</em></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['name' => 'Basic', 'price' => '25.000', 'duration' => '1 Sesi (5 foto)', 'features' => ['5 Foto HD', '10 Pilihan Frame', 'Download Digital', 'Filter Standar'], 'highlight' => false],
                ['name' => 'Premium', 'price' => '50.000', 'duration' => '1 Sesi (15 foto)', 'features' => ['15 Foto HD', '30+ Pilihan Frame', 'Download + Print', 'Filter Premium', 'GIF Animasi'], 'highlight' => true],
                ['name' => 'Party', 'price' => '150.000', 'duration' => 'Unlimited 1 Jam', 'features' => ['Foto Tak Terbatas', 'Semua Frame & Filter', 'Download + Print', 'GIF + Video Boomerang', 'Props Eksklusif', 'Operator Dedicated'], 'highlight' => false],
            ] as $pkg)
            <div class="relative border p-8 {{ $pkg['highlight'] ? 'border-gold bg-obsidian' : 'border-charcoal bg-obsidian/50' }}">
                @if($pkg['highlight'])
                <div class="absolute -top-3 left-8">
                    <span class="bg-gold text-obsidian text-xs font-semibold px-4 py-1 uppercase tracking-wider">Terpopuler</span>
                </div>
                @endif
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-2">{{ $pkg['name'] }}</p>
                <div class="flex items-end gap-1 mb-1">
                    <span class="text-cream/40 text-sm font-body">Rp</span>
                    <span class="font-display text-4xl font-bold {{ $pkg['highlight'] ? 'text-gold' : 'text-cream' }}">{{ $pkg['price'] }}</span>
                </div>
                <p class="text-cream/40 text-xs mb-8">{{ $pkg['duration'] }}</p>
                <div class="space-y-3 mb-8">
                    @foreach($pkg['features'] as $feat)
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-1.5 bg-gold rounded-full flex-shrink-0"></div>
                        <span class="text-cream/70 text-sm">{{ $feat }}</span>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('photobooth') }}"
                   class="block text-center py-3 text-sm font-semibold transition-all {{ $pkg['highlight'] ? 'bg-gold text-obsidian hover:bg-gold-light' : 'border border-charcoal text-cream/70 hover:border-gold hover:text-gold' }}">
                    Pilih Paket
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- RECENT PHOTOS --}}
@if(count($recent_photos ?? []) > 0)
<section class="py-28 max-w-7xl mx-auto px-6">
    <div class="flex items-end justify-between mb-12">
        <div>
            <p class="text-gold text-xs uppercase tracking-[0.3em] mb-4">Foto Terbaru</p>
            <h2 class="font-display text-4xl font-bold text-cream">Dari Studio Kami</h2>
        </div>
        <a href="{{ route('gallery') }}" class="text-gold/70 text-sm hover:text-gold transition-colors">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($recent_photos as $photo)
        <div class="photo-frame aspect-[3/4] bg-charcoal overflow-hidden">
            <img src="{{ Storage::url($photo->file_path) }}" alt="Photo" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity">
        </div>
        @endforeach
    </div>
</section>
@endif

@endsection
