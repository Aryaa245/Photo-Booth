@extends('layouts.app')
@section('title', 'Photobooth')

@push('styles')
<style>
    #video-container {
        position: relative;
        background: #111;
    }
    #preview-canvas {
        display: none;
    }
    .countdown-overlay {
        font-family: 'Playfair Display', serif;
        animation: countPulse 0.8s ease-out;
    }
    @keyframes countPulse {
        0% { transform: scale(1.5); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: scale(1); opacity: 0.9; }
    }
    .flash-overlay {
        animation: flashAnim 0.3s ease-out;
    }
    @keyframes flashAnim {
        0% { opacity: 0; }
        50% { opacity: 1; }
        100% { opacity: 0; }
    }
    .filter-btn.active {
        border-color: #C9A96E;
        color: #C9A96E;
    }
    .strip-photo {
        border: 2px solid #2A2A2A;
        transition: all 0.2s;
    }
    .strip-photo:hover { border-color: #C9A96E; }

    /* CSS Filters */
    .filter-normal { filter: none; }
    .filter-bw { filter: grayscale(100%); }
    .filter-sepia { filter: sepia(80%); }
    .filter-vintage { filter: sepia(40%) contrast(1.1) brightness(0.9); }
    .filter-cool { filter: hue-rotate(180deg) saturate(1.2); }
    .filter-warm { filter: saturate(1.3) hue-rotate(-20deg) brightness(1.05); }
</style>
@endpush

@section('content')
<div class="min-h-screen py-12 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-10">
            <p class="text-gold text-xs uppercase tracking-[0.3em] mb-3">Studio</p>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-cream">
                Photobooth <em class="text-gold/80 not-italic">Digital</em>
            </h1>
            <p class="text-cream/50 mt-3 text-sm">Izinkan akses kamera, pilih frame, lalu ambil foto!</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Camera + Controls --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Camera view --}}
                <div id="video-container" class="relative aspect-[4/3] bg-charcoal overflow-hidden border border-charcoal">
                    {{-- Video feed --}}
                    <video id="video" autoplay playsinline muted
                           class="w-full h-full object-cover filter-normal" id="camera-video">
                    </video>

                    {{-- Canvas (hidden, for capture) --}}
                    <canvas id="preview-canvas"></canvas>

                    {{-- Frame overlay --}}
                    <div id="frame-overlay" class="absolute inset-0 pointer-events-none z-10"></div>

                    {{-- Corner marks --}}
                    <div class="absolute top-3 left-3 w-5 h-5 border-t-2 border-l-2 border-gold/60 pointer-events-none z-20"></div>
                    <div class="absolute top-3 right-3 w-5 h-5 border-t-2 border-r-2 border-gold/60 pointer-events-none z-20"></div>
                    <div class="absolute bottom-3 left-3 w-5 h-5 border-b-2 border-l-2 border-gold/60 pointer-events-none z-20"></div>
                    <div class="absolute bottom-3 right-3 w-5 h-5 border-b-2 border-r-2 border-gold/60 pointer-events-none z-20"></div>

                    {{-- Countdown overlay --}}
                    <div id="countdown-overlay" class="hidden absolute inset-0 flex items-center justify-center z-30 pointer-events-none">
                        <span class="countdown-overlay text-9xl font-bold text-gold drop-shadow-2xl" id="countdown-num">3</span>
                    </div>

                    {{-- Flash effect --}}
                    <div id="flash-overlay" class="hidden absolute inset-0 bg-white z-40 pointer-events-none"></div>

                    {{-- Camera OFF state --}}
                    <div id="camera-off" class="absolute inset-0 flex flex-col items-center justify-center bg-obsidian z-20">
                        <svg class="w-16 h-16 text-charcoal mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-cream/50 text-sm mb-4">Kamera belum aktif</p>
                        <button onclick="startCamera()"
                                class="bg-gold text-obsidian text-sm font-semibold px-6 py-2.5 hover:bg-gold-light transition-colors">
                            Aktifkan Kamera
                        </button>
                    </div>
                </div>

                {{-- Filter selector --}}
                <div>
                    <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Filter</p>
                    <div class="flex gap-2 flex-wrap">
                        @foreach(['Normal' => 'normal', 'B&W' => 'bw', 'Sepia' => 'sepia', 'Vintage' => 'vintage', 'Cool' => 'cool', 'Warm' => 'warm'] as $label => $val)
                        <button onclick="setFilter('{{ $val }}')"
                                class="filter-btn text-xs text-cream/60 border border-charcoal px-4 py-2 hover:border-gold hover:text-gold transition-all {{ $val === 'normal' ? 'active' : '' }}"
                                data-filter="{{ $val }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Controls --}}
                <div class="flex items-center justify-between gap-4 pt-2">
                    {{-- Photos taken counter --}}
                    <div class="text-center">
                        <p class="text-gold font-display text-2xl font-bold" id="photo-count">0</p>
                        <p class="text-cream/40 text-xs">Foto diambil</p>
                    </div>

                    {{-- Shutter button --}}
                    <button id="shutter-btn" onclick="takePhoto()"
                            class="shutter-btn w-20 h-20 rounded-full border-4 border-gold flex items-center justify-center bg-gold/10 hover:bg-gold/20 transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                            disabled>
                        <div class="w-12 h-12 rounded-full bg-gold"></div>
                    </button>

                    {{-- Strip mode toggle --}}
                    <div class="text-center">
                        <label class="relative inline-flex items-center cursor-pointer mb-1">
                            <input type="checkbox" id="strip-mode" class="sr-only peer" onchange="toggleStripMode()">
                            <div class="w-10 h-5 bg-charcoal peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-cream after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold"></div>
                        </label>
                        <p class="text-cream/40 text-xs">Strip Mode</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Frame selector + Photo strip --}}
            <div class="space-y-4">

                {{-- Frame selector --}}
                <div>
                    <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Pilih Frame</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($frames as $frame)
                        <button onclick="selectFrame({{ $frame['id'] }}, '{{ $frame['preview_color'] }}')"
                                class="frame-btn aspect-[3/4] border-2 border-charcoal hover:border-gold transition-all relative overflow-hidden"
                                data-frame="{{ $frame['id'] }}"
                                style="background: {{ $frame['preview_color'] }}">
                            <span class="absolute bottom-1 left-0 right-0 text-center text-xs text-white/70">{{ $frame['name'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Photo Strip Result --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-cream/50 text-xs uppercase tracking-widest">Hasil Foto</p>
                        <button id="clear-btn" onclick="clearPhotos()" class="text-xs text-cream/30 hover:text-cream/60 transition-colors hidden">
                            Hapus Semua
                        </button>
                    </div>
                    <div id="photo-strip" class="space-y-2 min-h-24">
                        <div id="empty-strip" class="text-center py-8 text-cream/20 text-sm border border-dashed border-charcoal">
                            Foto akan muncul di sini
                        </div>
                    </div>
                </div>

                {{-- Save button --}}
                <div id="save-section" class="hidden space-y-2 pt-2">
                    <button onclick="saveStrip()"
                            class="w-full bg-gold text-obsidian font-semibold py-3 text-sm hover:bg-gold-light transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Simpan & Download
                    </button>
                    <button onclick="uploadPhotos()"
                            class="w-full border border-charcoal text-cream/60 py-3 text-sm hover:border-gold hover:text-gold transition-all">
                        Simpan ke Galeri
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden canvas for compositing --}}
<canvas id="composite-canvas" class="hidden"></canvas>
@endsection

@push('scripts')
<script>
    let stream = null;
    let currentFilter = 'normal';
    let currentFrameId = null;
    let currentFrameColor = null;
    let photos = [];
    let stripMode = false;
    let isCapturing = false;

    const video = document.getElementById('video');
    const shutterBtn = document.getElementById('shutter-btn');
    const photoCountEl = document.getElementById('photo-count');
    const photoStrip = document.getElementById('photo-strip');
    const emptyStrip = document.getElementById('empty-strip');

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { width: 1280, height: 960, facingMode: 'user' },
                audio: false
            });
            video.srcObject = stream;
            document.getElementById('camera-off').classList.add('hidden');
            shutterBtn.disabled = false;
        } catch (err) {
            alert('Tidak dapat mengakses kamera. Pastikan Anda mengizinkan akses kamera.');
        }
    }

    function setFilter(filter) {
        currentFilter = filter;
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
        video.className = `w-full h-full object-cover filter-${filter}`;
    }

    function selectFrame(id, color) {
        currentFrameId = id;
        currentFrameColor = color;
        document.querySelectorAll('.frame-btn').forEach(btn => btn.classList.remove('border-gold'));
        document.querySelector(`[data-frame="${id}"]`).classList.add('border-gold');

        // Update frame overlay
        const overlay = document.getElementById('frame-overlay');
        overlay.style.border = `12px solid ${color}`;
    }

    function toggleStripMode() {
        stripMode = document.getElementById('strip-mode').checked;
    }

    async function takePhoto() {
        if (isCapturing || !stream) return;
        isCapturing = true;

        // Countdown
        if (stripMode || photos.length === 0) {
            await countdown();
        }

        // Flash
        const flash = document.getElementById('flash-overlay');
        flash.classList.remove('hidden');
        flash.classList.add('flash-overlay');
        setTimeout(() => {
            flash.classList.add('hidden');
            flash.classList.remove('flash-overlay');
        }, 300);

        // Capture
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');

        // Apply filter via CSS filter simulation
        applyFilterToCanvas(ctx, canvas);

        // Add frame border
        if (currentFrameColor) {
            const borderW = 16;
            ctx.strokeStyle = currentFrameColor;
            ctx.lineWidth = borderW;
            ctx.strokeRect(borderW/2, borderW/2, canvas.width - borderW, canvas.height - borderW);
        }

        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        photos.push(dataUrl);

        renderPhotoStrip();

        photoCountEl.textContent = photos.length;

        if (stripMode && photos.length < 4) {
            isCapturing = false;
            setTimeout(takePhoto, 800);
        } else {
            isCapturing = false;
        }

        // Show save section if photos > 0
        if (photos.length > 0) {
            document.getElementById('save-section').classList.remove('hidden');
            document.getElementById('clear-btn').classList.remove('hidden');
        }
    }

    function applyFilterToCanvas(ctx, canvas) {
        const filterMap = {
            'bw': 'grayscale(100%)',
            'sepia': 'sepia(80%)',
            'vintage': 'sepia(40%) contrast(1.1) brightness(0.9)',
            'cool': 'hue-rotate(180deg) saturate(1.2)',
            'warm': 'saturate(1.3) hue-rotate(-20deg) brightness(1.05)',
            'normal': 'none'
        };
        ctx.filter = filterMap[currentFilter] || 'none';
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        ctx.filter = 'none';
    }

    function countdown() {
        return new Promise(resolve => {
            const overlay = document.getElementById('countdown-overlay');
            const numEl = document.getElementById('countdown-num');
            overlay.classList.remove('hidden');
            let count = 3;
            numEl.textContent = count;

            const interval = setInterval(() => {
                count--;
                if (count <= 0) {
                    clearInterval(interval);
                    overlay.classList.add('hidden');
                    resolve();
                } else {
                    numEl.textContent = count;
                }
            }, 1000);
        });
    }

    function renderPhotoStrip() {
        if (photos.length === 0) {
            emptyStrip.style.display = 'block';
            return;
        }
        emptyStrip.style.display = 'none';

        // Clear and re-render
        const existing = photoStrip.querySelectorAll('.strip-item');
        existing.forEach(el => el.remove());

        photos.forEach((src, idx) => {
            const div = document.createElement('div');
            div.className = 'strip-item strip-photo relative';
            div.innerHTML = `
                <img src="${src}" class="w-full aspect-[4/3] object-cover">
                <button onclick="removePhoto(${idx})"
                        class="absolute top-1 right-1 w-6 h-6 bg-obsidian/80 text-cream/60 hover:text-gold text-xs flex items-center justify-center">
                    ✕
                </button>
            `;
            photoStrip.appendChild(div);
        });
    }

    function removePhoto(idx) {
        photos.splice(idx, 1);
        photoCountEl.textContent = photos.length;
        renderPhotoStrip();
        if (photos.length === 0) {
            document.getElementById('save-section').classList.add('hidden');
            document.getElementById('clear-btn').classList.add('hidden');
        }
    }

    function clearPhotos() {
        photos = [];
        photoCountEl.textContent = 0;
        renderPhotoStrip();
        document.getElementById('save-section').classList.add('hidden');
        document.getElementById('clear-btn').classList.add('hidden');
    }

    function saveStrip() {
        if (photos.length === 0) return;

        const canvas = document.getElementById('composite-canvas');
        const photoW = 400;
        const photoH = 300;
        const padding = 16;
        const borderW = 20;

        canvas.width = photoW + padding * 2 + borderW * 2;
        canvas.height = (photoH + padding) * photos.length + padding + borderW * 2;

        const ctx = canvas.getContext('2d');

        // Background
        ctx.fillStyle = '#0D0D0D';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Border
        if (currentFrameColor) {
            ctx.strokeStyle = currentFrameColor;
            ctx.lineWidth = borderW;
            ctx.strokeRect(borderW/2, borderW/2, canvas.width - borderW, canvas.height - borderW);
        }

        // Draw photos
        let loaded = 0;
        photos.forEach((src, idx) => {
            const img = new Image();
            img.onload = () => {
                const x = borderW + padding;
                const y = borderW + padding + idx * (photoH + padding);
                ctx.drawImage(img, x, y, photoW, photoH);
                loaded++;
                if (loaded === photos.length) {
                    // Add watermark
                    ctx.fillStyle = 'rgba(201, 169, 110, 0.4)';
                    ctx.font = 'italic 14px Playfair Display, serif';
                    ctx.textAlign = 'center';
                    ctx.fillText('SnapStudio', canvas.width / 2, canvas.height - 8);

                    // Download
                    const link = document.createElement('a');
                    link.download = `snapstudio-${Date.now()}.jpg`;
                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                    link.click();
                }
            };
            img.src = src;
        });
    }

    async function uploadPhotos() {
        if (photos.length === 0) return;

        const btn = event.target;
        btn.textContent = 'Menyimpan...';
        btn.disabled = true;

        try {
            const response = await fetch('{{ route("photos.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    photos: photos,
                    frame_id: currentFrameId,
                    filter: currentFilter
                })
            });

            if (response.ok) {
                btn.textContent = '✓ Tersimpan di Galeri!';
                btn.classList.add('text-gold', 'border-gold');
                setTimeout(() => {
                    window.location.href = '{{ route("gallery") }}';
                }, 1500);
            } else {
                throw new Error('Gagal menyimpan');
            }
        } catch (err) {
            btn.textContent = 'Coba Lagi';
            btn.disabled = false;
        }
    }

    // Auto-start camera on page load
    window.addEventListener('load', startCamera);
</script>
@endpush
