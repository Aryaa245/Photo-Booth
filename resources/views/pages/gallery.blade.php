@extends('layouts.app')
@section('title', 'Galeri Foto')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-16">

    {{-- Header --}}
    <div class="text-center mb-16">
        <p class="text-gold text-xs uppercase tracking-[0.3em] mb-4">Koleksi</p>
        <h1 class="font-display text-5xl font-bold text-cream mb-4">Galeri <em class="text-gold/80 not-italic">Foto</em></h1>
        <p class="text-cream/50 text-sm max-w-md mx-auto">Kenangan indah dari setiap sesi photobooth bersama orang-orang tersayang.</p>
    </div>

    {{-- Stats bar --}}
    <div class="grid grid-cols-3 gap-px bg-charcoal mb-16">
        <div class="bg-film py-6 text-center">
            <p class="font-display text-3xl font-bold text-gold">{{ $photos->total() }}</p>
            <p class="text-cream/40 text-xs mt-1">Total Foto</p>
        </div>
        <div class="bg-film py-6 text-center">
            <p class="font-display text-3xl font-bold text-gold">{{ $total_sessions ?? 0 }}</p>
            <p class="text-cream/40 text-xs mt-1">Sesi</p>
        </div>
        <div class="bg-film py-6 text-center">
            <p class="font-display text-3xl font-bold text-gold">{{ $today_count ?? 0 }}</p>
            <p class="text-cream/40 text-xs mt-1">Hari ini</p>
        </div>
    </div>

    {{-- Gallery Grid --}}
    @if($photos->count() > 0)
    <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
        @foreach($photos as $photo)
        <div class="photo-frame break-inside-avoid relative group cursor-pointer"
             onclick="openLightbox('{{ Storage::url($photo->file_path) }}', '{{ $photo->created_at->format('d M Y') }}')">
            <img src="{{ Storage::url($photo->file_path) }}"
                 alt="Photo {{ $photo->id }}"
                 class="w-full object-cover opacity-80 group-hover:opacity-100 transition-all duration-300">

            {{-- Hover overlay --}}
            <div class="absolute inset-0 bg-obsidian/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                </svg>
            </div>

            {{-- Date tag --}}
            <div class="absolute bottom-2 left-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <span class="bg-obsidian/80 text-gold text-xs px-2 py-1">{{ $photo->created_at->format('d M') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-16 flex justify-center">
        {{ $photos->links() }}
    </div>

    @else
    {{-- Empty state --}}
    <div class="text-center py-24 border border-dashed border-charcoal">
        <svg class="w-16 h-16 text-charcoal mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-cream/30 mb-6">Belum ada foto di galeri</p>
        <a href="{{ route('photobooth') }}" class="inline-flex items-center gap-2 bg-gold text-obsidian text-sm font-semibold px-6 py-3 hover:bg-gold-light transition-colors">
            Ambil Foto Pertama
        </a>
    </div>
    @endif
</div>

{{-- Lightbox --}}
<div id="lightbox" class="fixed inset-0 bg-obsidian/95 z-50 hidden items-center justify-center" onclick="closeLightbox()">
    <div class="relative max-w-2xl w-full mx-4" onclick="event.stopPropagation()">
        {{-- Close --}}
        <button onclick="closeLightbox()" class="absolute -top-10 right-0 text-cream/50 hover:text-gold transition-colors text-sm">
            ESC — Tutup
        </button>

        {{-- Image --}}
        <img id="lightbox-img" src="" alt="Foto" class="w-full max-h-[80vh] object-contain border border-charcoal">

        {{-- Caption --}}
        <p id="lightbox-date" class="text-center text-gold/60 text-xs mt-3 font-display italic"></p>

        {{-- Actions --}}
        <div class="flex gap-3 mt-4 justify-center">
            <a id="lightbox-download" href="#" download class="border border-charcoal text-cream/60 text-sm px-5 py-2.5 hover:border-gold hover:text-gold transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openLightbox(src, date) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox-date').textContent = date;
        document.getElementById('lightbox-download').href = src;
        const lb = document.getElementById('lightbox');
        lb.classList.remove('hidden');
        lb.classList.add('flex');
    }

    function closeLightbox() {
        const lb = document.getElementById('lightbox');
        lb.classList.add('hidden');
        lb.classList.remove('flex');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endpush
