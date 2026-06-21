@extends('layouts.app')
@section('title', 'Photobooth')

@push('styles')
<style>
    /* CSS Filters */
    .filter-normal  { filter: none; }
    .filter-bw      { filter: grayscale(100%); }
    .filter-sepia   { filter: sepia(80%); }
    .filter-vintage { filter: sepia(40%) contrast(1.1) brightness(0.9); }
    .filter-cool    { filter: hue-rotate(180deg) saturate(1.2); }
    .filter-warm    { filter: saturate(1.3) hue-rotate(-20deg) brightness(1.05); }

    .filter-btn.active  { border-color: #C9A96E; color: #C9A96E; }
    .template-btn.active { ring: 2px solid #C9A96E; }

    @keyframes countPulse {
        0%   { transform: scale(1.5); opacity: 0; }
        50%  { opacity: 1; }
        100% { transform: scale(1); opacity: 0.9; }
    }
    .countdown-overlay { animation: countPulse 0.8s ease-out; }

    @keyframes flashAnim {
        0%   { opacity: 0; }
        50%  { opacity: 1; }
        100% { opacity: 0; }
    }
    .flash-overlay { animation: flashAnim 0.3s ease-out; }

    /* Tab active */
    .tab-btn.active { background: #C9A96E; color: #0D0D0D; }

    /* Result strip canvas area */
    #result-area { background: #111; min-height: 200px; }

    /* Template thumbnail */
    .tmpl-card { cursor: pointer; transition: all 0.2s; border: 2px solid #2A2A2A; }
    .tmpl-card:hover, .tmpl-card.active { border-color: #C9A96E; }
    .tmpl-card.active .tmpl-label { color: #C9A96E; }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 px-4">
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-8">
        <p class="text-gold text-xs uppercase tracking-[0.3em] mb-2">Studio</p>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-cream">
            Photobooth <em class="text-gold/80 not-italic">Digital</em>
        </h1>
    </div>

    {{-- MODE TABS --}}
    <div class="flex justify-center gap-2 mb-8 flex-wrap">
        <button onclick="setMode('classic')" id="tab-classic"
                class="tab-btn active text-xs font-semibold px-4 py-2 border border-charcoal transition-all">
            📷 Classic
        </button>
        <button onclick="setMode('photodump')" id="tab-photodump"
                class="tab-btn text-xs font-semibold px-4 py-2 border border-charcoal text-cream/60 transition-all">
            🌸 Photo Dump
        </button>
        <button onclick="setMode('popstrip')" id="tab-popstrip"
                class="tab-btn text-xs font-semibold px-4 py-2 border border-charcoal text-cream/60 transition-all">
            💥 Pop Strip
        </button>
        <button onclick="setMode('cinema')" id="tab-cinema"
                class="tab-btn text-xs font-semibold px-4 py-2 border border-charcoal text-cream/60 transition-all">
            🎬 Cinema
        </button>
        <button onclick="setMode('retrocollage')" id="tab-retrocollage"
                class="tab-btn text-xs font-semibold px-4 py-2 border border-charcoal text-cream/60 transition-all">
            🎵 Retro
        </button>
        <button onclick="setMode('musicplayer')" id="tab-musicplayer"
                class="tab-btn text-xs font-semibold px-4 py-2 border border-charcoal text-cream/60 transition-all">
            🎶 Music
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Camera --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Camera view --}}
            <div id="video-container" class="relative aspect-[4/3] bg-charcoal overflow-hidden border border-charcoal">
                <video id="video" autoplay playsinline muted class="w-full h-full object-cover filter-normal"></video>
                <canvas id="preview-canvas" class="hidden"></canvas>

                {{-- Frame overlay (classic mode) --}}
                <div id="frame-overlay" class="absolute inset-0 pointer-events-none z-10"></div>

                {{-- Template overlay preview --}}
                <div id="template-preview" class="absolute inset-0 pointer-events-none z-10 hidden"></div>

                {{-- Corner marks --}}
                <div class="absolute top-3 left-3 w-5 h-5 border-t-2 border-l-2 border-gold/60 pointer-events-none z-20"></div>
                <div class="absolute top-3 right-3 w-5 h-5 border-t-2 border-r-2 border-gold/60 pointer-events-none z-20"></div>
                <div class="absolute bottom-3 left-3 w-5 h-5 border-b-2 border-l-2 border-gold/60 pointer-events-none z-20"></div>
                <div class="absolute bottom-3 right-3 w-5 h-5 border-b-2 border-r-2 border-gold/60 pointer-events-none z-20"></div>

                {{-- Countdown --}}
                <div id="countdown-overlay" class="hidden absolute inset-0 flex items-center justify-center z-30 pointer-events-none">
                    <span class="countdown-overlay font-display text-9xl font-bold text-gold drop-shadow-2xl" id="countdown-num">3</span>
                </div>

                {{-- Flash --}}
                <div id="flash-overlay" class="hidden absolute inset-0 bg-white z-40 pointer-events-none"></div>

                {{-- Camera OFF --}}
                <div id="camera-off" class="absolute inset-0 flex flex-col items-center justify-center bg-obsidian z-20">
                    <svg class="w-14 h-14 text-charcoal mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-cream/50 text-sm mb-4">Kamera belum aktif</p>
                    <button onclick="startCamera()" class="bg-gold text-obsidian text-sm font-semibold px-6 py-2.5 hover:bg-gold-light transition-colors">
                        Aktifkan Kamera
                    </button>
                </div>

                {{-- Mode info badge --}}
                <div id="mode-badge" class="absolute top-3 left-1/2 -translate-x-1/2 z-20 pointer-events-none">
                    <span id="mode-badge-text" class="bg-obsidian/70 text-gold text-xs px-3 py-1 font-semibold"></span>
                </div>
            </div>

            {{-- Filter --}}
            <div>
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-2">Filter</p>
                <div class="flex gap-2 flex-wrap">
                    @foreach(['Normal'=>'normal','B&W'=>'bw','Sepia'=>'sepia','Vintage'=>'vintage','Cool'=>'cool','Warm'=>'warm'] as $label=>$val)
                    <button onclick="setFilter('{{ $val }}')"
                            class="filter-btn text-xs text-cream/60 border border-charcoal px-4 py-2 hover:border-gold hover:text-gold transition-all {{ $val==='normal'?'active':'' }}"
                            data-filter="{{ $val }}">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            {{-- Controls --}}
            <div class="flex items-center justify-between pt-2">
                <div class="text-center">
                    <p class="text-gold font-display text-2xl font-bold" id="photo-count">0</p>
                    <p class="text-cream/40 text-xs">Foto diambil</p>
                    <p class="text-gold/60 text-xs mt-1" id="photos-needed"></p>
                </div>

                <button id="shutter-btn" onclick="takePhoto()"
                        class="w-20 h-20 rounded-full border-4 border-gold flex items-center justify-center bg-gold/10 hover:bg-gold/20 transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>
                    <div class="w-12 h-12 rounded-full bg-gold"></div>
                </button>

                <button onclick="clearPhotos()" class="text-xs text-cream/30 hover:text-cream/60 transition-colors text-center">
                    <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Reset
                </button>
            </div>
        </div>

        {{-- RIGHT: Template / Frame selector + Preview --}}
        <div class="space-y-5">

            {{-- CLASSIC: Frame color picker --}}
            <div id="panel-classic">
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Pilih Frame</p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach([
                        ['id'=>1,'name'=>'Gold','color'=>'#C9A96E'],
                        ['id'=>2,'name'=>'Hitam','color'=>'#1A1A1A'],
                        ['id'=>3,'name'=>'Putih','color'=>'#F5F0E8'],
                        ['id'=>4,'name'=>'Pink','color'=>'#E8A0A0'],
                        ['id'=>5,'name'=>'Biru','color'=>'#7BA7BC'],
                        ['id'=>6,'name'=>'Hijau','color'=>'#6B9E78'],
                    ] as $f)
                    <button onclick="selectFrame({{ $f['id'] }},'{{ $f['color'] }}')"
                            class="tmpl-card aspect-[3/4] relative overflow-hidden flex flex-col items-center justify-center"
                            data-frame="{{ $f['id'] }}" style="background:{{ $f['color'] }}22; border-color:#2A2A2A">
                        <div class="w-8 h-8 rounded-full mb-1" style="background:{{ $f['color'] }}"></div>
                        <span class="tmpl-label text-cream/60 text-xs">{{ $f['name'] }}</span>
                    </button>
                    @endforeach
                </div>
                <div class="mt-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="strip-mode" class="sr-only peer" onchange="toggleStripMode()">
                        <div class="w-10 h-5 bg-charcoal rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-cream after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold relative"></div>
                        <span class="text-cream/50 text-xs">Strip Mode (4 foto)</span>
                    </label>
                </div>
            </div>

            {{-- PHOTO DUMP template selector --}}
            <div id="panel-photodump" class="hidden">
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Pilih Style Photo Dump</p>
                <p class="text-gold/60 text-xs mb-3">📸 Butuh <strong>5 foto</strong> untuk template ini</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                        ['id'=>'dump-beige','name'=>'Retro Beige','bg'=>'#D4B896','accent'=>'#8B4513'],
                        ['id'=>'dump-pastel','name'=>'Pastel Dream','bg'=>'#F0E6FF','accent'=>'#9B59B6'],
                        ['id'=>'dump-mint','name'=>'Mint Fresh','bg'=>'#E0F5F0','accent'=>'#27AE60'],
                        ['id'=>'dump-dark','name'=>'Dark Collage','bg'=>'#1A1A2E','accent'=>'#E94560'],
                    ] as $t)
                    <button onclick="selectTemplate('{{ $t['id'] }}')"
                            class="tmpl-card p-3 text-left"
                            data-template="{{ $t['id'] }}"
                            style="background:{{ $t['bg'] }}33">
                        <div class="w-full h-16 mb-2 rounded flex items-center justify-center text-2xl"
                             style="background:{{ $t['bg'] }}">📷</div>
                        <p class="tmpl-label text-cream/70 text-xs font-medium">{{ $t['name'] }}</p>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- POP STRIP template selector --}}
            <div id="panel-popstrip" class="hidden">
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Pilih Style Pop Strip</p>
                <p class="text-gold/60 text-xs mb-3">📸 Butuh <strong>3 foto</strong> untuk template ini</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                        ['id'=>'pop-yellow','name'=>'Pop Yellow','emoji'=>'💥'],
                        ['id'=>'pop-pink','name'=>'Pop Pink','emoji'=>'🌸'],
                        ['id'=>'pop-blue','name'=>'Pop Blue','emoji'=>'⚡'],
                        ['id'=>'pop-green','name'=>'Pop Green','emoji'=>'🌿'],
                    ] as $t)
                    <button onclick="selectTemplate('{{ $t['id'] }}')"
                            class="tmpl-card p-3 text-left"
                            data-template="{{ $t['id'] }}">
                        <div class="w-full h-16 mb-2 rounded flex items-center justify-center text-3xl bg-charcoal">
                            {{ $t['emoji'] }}
                        </div>
                        <p class="tmpl-label text-cream/70 text-xs font-medium">{{ $t['name'] }}</p>
                    </button>
                    @endforeach
                </div>
            </div>


            {{-- CINEMA template selector --}}
            <div id="panel-cinema" class="hidden">
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Pilih Style Cinema</p>
                <p class="text-gold/60 text-xs mb-3">📸 Butuh <strong>5 foto</strong> untuk template ini</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                        ['id'=>'cinema-red','name'=>'Red Theater','emoji'=>'🎭','bg'=>'#8B0000'],
                        ['id'=>'cinema-gold','name'=>'Gold Premiere','emoji'=>'⭐','bg'=>'#8B6914'],
                        ['id'=>'cinema-night','name'=>'Night Show','emoji'=>'🌙','bg'=>'#1a1a3e'],
                        ['id'=>'cinema-vintage','name'=>'Vintage Film','emoji'=>'🎞','bg'=>'#4a3728'],
                    ] as $t)
                    <button onclick="selectTemplate('{{ $t['id'] }}')"
                            class="tmpl-card p-3 text-left"
                            data-template="{{ $t['id'] }}"
                            style="background:{{ $t['bg'] }}55">
                        <div class="w-full h-16 mb-2 rounded flex items-center justify-center text-3xl"
                             style="background:{{ $t['bg'] }}">{{ $t['emoji'] }}</div>
                        <p class="tmpl-label text-cream/70 text-xs font-medium">{{ $t['name'] }}</p>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- RETRO COLLAGE template selector --}}
            <div id="panel-retrocollage" class="hidden">
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Pilih Style Retro</p>
                <p class="text-gold/60 text-xs mb-3">📸 Butuh <strong>6 foto</strong> untuk template ini</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach([
                        ['id'=>'retro-warm','name'=>'Warm Retro','emoji'=>'📻','bg'=>'#f5e6d0'],
                        ['id'=>'retro-cool','name'=>'Cool Vintage','emoji'=>'💿','bg'=>'#d0e6f5'],
                        ['id'=>'retro-dark','name'=>'Dark Film','emoji'=>'🎞','bg'=>'#1a1a1a'],
                        ['id'=>'retro-pastel','name'=>'Pastel Mix','emoji'=>'🌈','bg'=>'#f0e0ff'],
                    ] as $t)
                    <button onclick="selectTemplate('{{ $t['id'] }}')"
                            class="tmpl-card p-3 text-left"
                            data-template="{{ $t['id'] }}"
                            style="background:{{ $t['bg'] }}">
                        <div class="w-full h-16 mb-2 rounded flex items-center justify-center text-3xl"
                             style="background:{{ $t['bg'] }}cc">{{ $t['emoji'] }}</div>
                        <p class="tmpl-label text-cream/70 text-xs font-medium">{{ $t['name'] }}</p>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- MUSIC PLAYER template selector --}}
            <div id="panel-musicplayer" class="hidden">
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Pilih Style Music</p>
                <p class="text-gold/60 text-xs mb-3">📸 Butuh <strong>3 foto</strong> untuk template ini</p>
                <div class="mt-2 space-y-3">
                    <div>
                        <label class="text-cream/40 text-xs mb-1 block">Nama Lagu</label>
                        <input type="text" id="music-title" value="Your Favorite Playlist"
                               class="w-full bg-charcoal border border-charcoal text-cream text-xs px-3 py-2 focus:border-gold outline-none">
                    </div>
                    <div>
                        <label class="text-cream/40 text-xs mb-1 block">Nama Artis</label>
                        <input type="text" id="music-artist" value="SnapStudio"
                               class="w-full bg-charcoal border border-charcoal text-cream text-xs px-3 py-2 focus:border-gold outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['id'=>'music-green','name'=>'Spotify Green','bg'=>'#1DB954'],
                            ['id'=>'music-dark','name'=>'Dark Mode','bg'=>'#121212'],
                            ['id'=>'music-purple','name'=>'Neon Purple','bg'=>'#7B2FBE'],
                            ['id'=>'music-blue','name'=>'Ocean Blue','bg'=>'#0F4C75'],
                        ] as $t)
                        <button onclick="selectTemplate('{{ $t['id'] }}')"
                                class="tmpl-card p-2 text-center"
                                data-template="{{ $t['id'] }}"
                                style="background:{{ $t['bg'] }}33">
                            <div class="w-full h-10 mb-1 rounded" style="background:{{ $t['bg'] }}"></div>
                            <p class="tmpl-label text-cream/70 text-xs">{{ $t['name'] }}</p>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Result Preview --}}
            <div>
                <p class="text-cream/50 text-xs uppercase tracking-widest mb-3">Preview Hasil</p>
                <div id="result-area" class="border border-charcoal flex items-center justify-center min-h-40 p-2">
                    <p class="text-cream/20 text-xs text-center">Ambil foto untuk melihat preview</p>
                </div>
            </div>

            {{-- Save buttons --}}
            <div id="save-section" class="hidden space-y-2">
                <button onclick="generateAndDownload()"
                        class="w-full bg-gold text-obsidian font-semibold py-3 text-sm hover:bg-gold-light transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Foto
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

{{-- Hidden composite canvas --}}
<canvas id="composite-canvas" class="hidden"></canvas>
@endsection

@push('scripts')
<script>
// ============================================================
// STATE
// ============================================================
let stream = null;
let currentFilter = 'normal';
let currentMode = 'classic';       // classic | photodump | popstrip
let currentFrameColor = '#C9A96E';
let currentFrameId = 1;
let currentTemplate = null;
let photos = [];
let isCapturing = false;
let stripMode = false;

const video       = document.getElementById('video');
const shutterBtn  = document.getElementById('shutter-btn');
const photoCountEl= document.getElementById('photo-count');
const photosNeededEl = document.getElementById('photos-needed');

const PHOTOS_NEEDED = { classic: null, photodump: 5, popstrip: 3, cinema: 5, retrocollage: 6, musicplayer: 3 };

// ============================================================
// CAMERA
// ============================================================
async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { width:1280, height:960, facingMode:'user' }, audio:false });
        video.srcObject = stream;
        document.getElementById('camera-off').classList.add('hidden');
        shutterBtn.disabled = false;
    } catch(e) {
        alert('Tidak dapat mengakses kamera.');
    }
}

// ============================================================
// MODE SWITCHING
// ============================================================
function setMode(mode) {
    currentMode = mode;
    photos = [];
    photoCountEl.textContent = 0;
    renderResultArea();
    document.getElementById('save-section').classList.add('hidden');

    // tabs
    ['classic','photodump','popstrip','cinema','retrocollage','musicplayer'].forEach(m => {
        const tab = document.getElementById('tab-' + m);
        tab.classList.toggle('active', m === mode);
        tab.classList.toggle('text-cream/60', m !== mode);
        document.getElementById('panel-' + m).classList.toggle('hidden', m !== mode);
    });

    // badge
    const labels = { classic:'📷 Classic', photodump:'🌸 Photo Dump — 5 Foto', popstrip:'💥 Pop Strip — 3 Foto', cinema:'🎬 Cinema — 5 Foto', retrocollage:'🎵 Retro Collage — 6 Foto', musicplayer:'🎶 Music Player — 3 Foto' };
    document.getElementById('mode-badge-text').textContent = labels[mode];

    // needed
    const n = PHOTOS_NEEDED[mode];
    photosNeededEl.textContent = n ? `Butuh ${n} foto` : '';

    // template overlay
    document.getElementById('template-preview').classList.add('hidden');
    document.getElementById('frame-overlay').style.border = '';
}

// ============================================================
// FRAME (classic)
// ============================================================
function selectFrame(id, color) {
    currentFrameId = id; currentFrameColor = color;
    document.querySelectorAll('[data-frame]').forEach(b => b.classList.remove('active'));
    document.querySelector(`[data-frame="${id}"]`).classList.add('active');
    document.getElementById('frame-overlay').style.cssText = `border: 14px solid ${color};`;
}

// ============================================================
// TEMPLATE (photodump / popstrip)
// ============================================================
function selectTemplate(id) {
    currentTemplate = id;
    document.querySelectorAll('[data-template]').forEach(b => b.classList.remove('active'));
    document.querySelector(`[data-template="${id}"]`).classList.add('active');
}

function toggleStripMode() {
    stripMode = document.getElementById('strip-mode').checked;
}

// ============================================================
// FILTER
// ============================================================
function setFilter(f) {
    currentFilter = f;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`[data-filter="${f}"]`).classList.add('active');
    video.className = `w-full h-full object-cover filter-${f}`;
}

// ============================================================
// CAPTURE
// ============================================================
async function takePhoto() {
    if (isCapturing || !stream) return;
    const needed = PHOTOS_NEEDED[currentMode];
    if (needed && photos.length >= needed) {
        alert(`Template ini sudah lengkap (${needed} foto). Reset dulu untuk mengambil lagi.`);
        return;
    }
    isCapturing = true;
    await countdown();
    flash();

    const canvas = document.createElement('canvas');
    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    const ctx = canvas.getContext('2d');
    const filterMap = { bw:'grayscale(100%)', sepia:'sepia(80%)', vintage:'sepia(40%) contrast(1.1) brightness(0.9)', cool:'hue-rotate(180deg) saturate(1.2)', warm:'saturate(1.3) hue-rotate(-20deg) brightness(1.05)', normal:'none' };
    ctx.filter = filterMap[currentFilter] || 'none';
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    ctx.filter = 'none';

    photos.push(canvas.toDataURL('image/jpeg', 0.92));
    photoCountEl.textContent = photos.length;
    renderResultArea();

    const n = PHOTOS_NEEDED[currentMode];
    if (n && photos.length >= n) {
        document.getElementById('save-section').classList.remove('hidden');
        photosNeededEl.textContent = '✓ Siap!';
    } else if (n) {
        photosNeededEl.textContent = `${photos.length}/${n} foto`;
    }

    // classic strip auto-shoot
    if (currentMode === 'classic' && stripMode && photos.length < 4) {
        setTimeout(() => { isCapturing = false; takePhoto(); }, 800);
        return;
    }
    if (currentMode === 'classic' && !stripMode) {
        document.getElementById('save-section').classList.remove('hidden');
    }
    isCapturing = false;
}

function countdown() {
    return new Promise(resolve => {
        const ov = document.getElementById('countdown-overlay');
        const num = document.getElementById('countdown-num');
        ov.classList.remove('hidden');
        let c = 3; num.textContent = c;
        const iv = setInterval(() => {
            c--; if (c <= 0) { clearInterval(iv); ov.classList.add('hidden'); resolve(); }
            else num.textContent = c;
        }, 1000);
    });
}

function flash() {
    const f = document.getElementById('flash-overlay');
    f.classList.remove('hidden'); f.classList.add('flash-overlay');
    setTimeout(() => { f.classList.add('hidden'); f.classList.remove('flash-overlay'); }, 300);
}

// ============================================================
// RENDER RESULT AREA (thumbnail strip)
// ============================================================
function renderResultArea() {
    const area = document.getElementById('result-area');
    if (photos.length === 0) {
        area.innerHTML = '<p class="text-cream/20 text-xs text-center">Ambil foto untuk melihat preview</p>';
        return;
    }
    const cols = currentMode === 'popstrip' ? 1 : 2;
    area.innerHTML = `<div class="grid gap-1 w-full" style="grid-template-columns:repeat(${cols},1fr)">` +
        photos.map((src,i) => `
            <div class="relative group">
                <img src="${src}" class="w-full object-cover aspect-[4/3]">
                <button onclick="removePhoto(${i})" class="absolute top-0.5 right-0.5 w-5 h-5 bg-obsidian/80 text-cream/60 hover:text-gold text-xs hidden group-hover:flex items-center justify-center">✕</button>
            </div>`).join('') + '</div>';
}

function removePhoto(i) {
    photos.splice(i,1);
    photoCountEl.textContent = photos.length;
    renderResultArea();
    const n = PHOTOS_NEEDED[currentMode];
    if (n) photosNeededEl.textContent = `${photos.length}/${n} foto`;
    if (photos.length === 0) document.getElementById('save-section').classList.add('hidden');
}

function clearPhotos() {
    photos = []; photoCountEl.textContent = 0;
    renderResultArea();
    document.getElementById('save-section').classList.add('hidden');
    const n = PHOTOS_NEEDED[currentMode];
    photosNeededEl.textContent = n ? `Butuh ${n} foto` : '';
}

// ============================================================
// GENERATE CANVAS BY MODE
// ============================================================
async function generateAndDownload() {
    const canvas = document.getElementById('composite-canvas');
    const ctx = canvas.getContext('2d');

    if (currentMode === 'classic') {
        await generateClassic(canvas, ctx);
    } else if (currentMode === 'photodump') {
        await generatePhotoDump(canvas, ctx);
    } else if (currentMode === 'popstrip') {
        await generatePopStrip(canvas, ctx);
    } else if (currentMode === 'cinema') {
        await generateCinema(canvas, ctx);
    } else if (currentMode === 'retrocollage') {
        await generateRetroCollage(canvas, ctx);
    } else if (currentMode === 'musicplayer') {
        await generateMusicPlayer(canvas, ctx);
    }

    const link = document.createElement('a');
    link.download = `snapstudio-${currentMode}-${Date.now()}.jpg`;
    link.href = canvas.toDataURL('image/jpeg', 0.95);
    link.click();
}

// ---- CLASSIC ----
async function generateClassic(canvas, ctx) {
    const W = 500, H = 400;
    const pad = 20;
    canvas.width  = W;
    canvas.height = H * photos.length + pad * (photos.length + 1);
    ctx.fillStyle = '#0D0D0D'; ctx.fillRect(0,0,canvas.width,canvas.height);
    for (let i=0; i<photos.length; i++) {
        const img = await loadImg(photos[i]);
        ctx.drawImage(img, pad, pad + i*(H+pad), W-pad*2, H);
        if (currentFrameColor) {
            ctx.strokeStyle = currentFrameColor; ctx.lineWidth = 14;
            ctx.strokeRect(pad, pad + i*(H+pad), W-pad*2, H);
        }
    }
    watermark(ctx, canvas.width, canvas.height);
}

// ---- PHOTO DUMP ----
async function generatePhotoDump(canvas, ctx) {
    const W = 900, H = 1350;
    canvas.width = W; canvas.height = H;

    const themes = {
        'dump-beige': { bg:'#D4B896', frame:'#FFFFFF', text:'#8B4513' },
        'dump-pastel':{ bg:'#F0E6FF', frame:'#FFFFFF', text:'#9B59B6' },
        'dump-mint':  { bg:'#E0F5F0', frame:'#FFFFFF', text:'#27AE60' },
        'dump-dark':  { bg:'#1A1A2E', frame:'#E94560', text:'#FFD700' },
    };
    const theme = themes[currentTemplate] || themes['dump-beige'];

    // Background
    ctx.fillStyle = theme.bg; ctx.fillRect(0,0,W,H);

    // Decorative handwriting lines (simulate)
    ctx.strokeStyle = theme.text + '20'; ctx.lineWidth = 1;
    for (let y=30; y<120; y+=18) {
        ctx.beginPath(); ctx.moveTo(20,y); ctx.lineTo(W-20,y); ctx.stroke();
    }

    // Title "photo dump"
    ctx.font = 'bold 72px serif';
    ctx.fillStyle = theme.text;
    ctx.fillText('photo dump', 50, 140);

    // Layout: 5 photos scattered
    const layouts = [
        { x:60,  y:160, w:380, h:300, rot:-4 },
        { x:430, y:120, w:350, h:270, rot: 3 },
        { x:80,  y:490, w:340, h:280, rot: 2 },
        { x:400, y:430, w:360, h:285, rot:-3 },
        { x:130, y:800, w:620, h:420, rot:-1 },
    ];

    for (let i=0; i<Math.min(photos.length, 5); i++) {
        const l = layouts[i];
        const img = await loadImg(photos[i]);
        ctx.save();
        ctx.translate(l.x + l.w/2, l.y + l.h/2);
        ctx.rotate(l.rot * Math.PI/180);
        // Shadow
        ctx.shadowColor = 'rgba(0,0,0,0.25)'; ctx.shadowBlur = 12; ctx.shadowOffsetX = 4; ctx.shadowOffsetY = 6;
        // White polaroid frame
        ctx.fillStyle = theme.frame;
        ctx.fillRect(-l.w/2-12, -l.h/2-12, l.w+24, l.h+50);
        ctx.shadowColor = 'transparent';
        ctx.drawImage(img, -l.w/2, -l.h/2, l.w, l.h);
        ctx.restore();
    }

    // Stars decoration
    ctx.fillStyle = theme.text;
    ['★','★','☆'].forEach((s,i) => {
        ctx.font = `${24+i*8}px serif`;
        ctx.fillText(s, W-100+i*28, 80+i*20);
    });

    // Bottom text
    ctx.font = 'italic 28px serif'; ctx.fillStyle = theme.text+'90';
    ctx.textAlign = 'center'; ctx.fillText('SnapStudio', W/2, H-30);
}

// ---- POP STRIP ----
async function generatePopStrip(canvas, ctx) {
    const W = 520, H = 1560;
    canvas.width = W; canvas.height = H;

    const themes = {
        'pop-yellow': { bg:'#F5E642', bg2:'#FFFFF0', frame1:'#4A90D9', frame2:'#90D9A0', frame3:'#FFB347', accent:'#FFD700' },
        'pop-pink':   { bg:'#FF69B4', bg2:'#FFF0F5', frame1:'#FF1493', frame2:'#FFB6C1', frame3:'#FF69B4', accent:'#FF1493' },
        'pop-blue':   { bg:'#1E90FF', bg2:'#F0F8FF', frame1:'#00BFFF', frame2:'#87CEEB', frame3:'#4169E1', accent:'#FFD700' },
        'pop-green':  { bg:'#2ECC71', bg2:'#F0FFF4', frame1:'#27AE60', frame2:'#A9DFBF', frame3:'#1ABC9C', accent:'#F39C12' },
    };
    const t = themes[currentTemplate] || themes['pop-yellow'];

    // Halftone background
    ctx.fillStyle = t.bg2; ctx.fillRect(0,0,W,H);
    // Dot pattern
    ctx.fillStyle = t.bg + '40';
    for (let x=0; x<W; x+=16) for (let y=0; y<H; y+=16) {
        ctx.beginPath(); ctx.arc(x,y,3,0,Math.PI*2); ctx.fill();
    }

    // 3 photo slots
    const slotH = 420, slotPad = 40, topPad = 60;
    const words = [['HA','HA','HA'], ['OMG!'], ['BOOM']];

    for (let i=0; i<3; i++) {
        const y = topPad + i*(slotH + slotPad);
        const cx = W/2, cy = y + slotH/2;
        const rx = 160, ry = 180;

        // Layered wavy frames
        const frameColors = [t.frame1, t.frame3, '#FFB6C1', t.frame2, '#FFFFFF'];
        frameColors.forEach((fc, fi) => {
            const off = fi * 10;
            ctx.strokeStyle = fc; ctx.lineWidth = 12;
            drawWavyRect(ctx, cx, cy, rx+off, ry+off);
        });

        // Photo inside
        if (photos[i]) {
            const img = await loadImg(photos[i]);
            ctx.save();
            // Clip to wavy shape
            drawWavyRect(ctx, cx, cy, rx-10, ry-10, true);
            ctx.clip();
            ctx.drawImage(img, cx-rx-10, cy-ry-10, (rx+10)*2, (ry+10)*2);
            ctx.restore();
        }

        // Stars inside frame
        ctx.fillStyle = t.accent; ctx.font = 'bold 20px sans-serif';
        [[cx-rx+20, cy-ry+30],[cx-rx+50, cy-ry+60],[cx+rx-40, cy+ry-50]].forEach(([sx,sy]) => {
            ctx.fillText('✦', sx, sy);
        });

        // Word art label
        const word = words[i]?.join(' ') || '';
        ctx.save();
        ctx.font = 'bold 52px Impact, sans-serif';
        ctx.fillStyle = t.accent;
        ctx.strokeStyle = '#000'; ctx.lineWidth = 6;
        ctx.textAlign = 'center';
        const ly = i===0 ? y+slotH-20 : y+slotH+20;
        if (i===0) {
            // Right side
            ctx.textAlign = 'right'; ctx.font = 'bold 38px Impact';
            words[0].forEach((w,wi) => { ctx.strokeText(w, W-20, y+80+wi*46); ctx.fillText(w, W-20, y+80+wi*46); });
        } else {
            ctx.strokeText(word, W/2, ly); ctx.fillText(word, W/2, ly);
        }
        ctx.restore();

        // Lightning bolts
        if (i===1) drawLightning(ctx, 30, y+slotH-60, t.accent);
        if (i===2) { drawLightning(ctx, 20, y+slotH+10, t.accent); drawLightning(ctx, W-20, y+slotH+10, t.accent, true); }
    }

    // Watermark
    ctx.fillStyle = '#00000040'; ctx.font = 'italic 20px serif'; ctx.textAlign = 'center';
    ctx.fillText('SnapStudio', W/2, H-15);
}

// ---- Wavy Rect helper ----
function drawWavyRect(ctx, cx, cy, rx, ry, fill=false) {
    const wave = 28;
    ctx.beginPath();
    ctx.moveTo(cx - rx + wave, cy - ry);
    ctx.bezierCurveTo(cx, cy - ry - wave, cx, cy - ry - wave, cx + rx - wave, cy - ry);
    ctx.bezierCurveTo(cx + rx + wave, cy - ry, cx + rx + wave, cy, cx + rx, cy);
    ctx.bezierCurveTo(cx + rx + wave, cy + ry, cx + rx, cy + ry + wave, cx + rx - wave, cy + ry);
    ctx.bezierCurveTo(cx, cy + ry + wave, cx, cy + ry + wave, cx - rx + wave, cy + ry);
    ctx.bezierCurveTo(cx - rx - wave, cy + ry, cx - rx - wave, cy, cx - rx, cy);
    ctx.bezierCurveTo(cx - rx - wave, cy - ry, cx - rx, cy - ry - wave, cx - rx + wave, cy - ry);
    ctx.closePath();
    if (fill) return;
    ctx.stroke();
}

function drawLightning(ctx, x, y, color, flip=false) {
    ctx.save(); ctx.strokeStyle = color; ctx.lineWidth = 4; ctx.fillStyle = color;
    if (flip) ctx.scale(-1,1), x=-x;
    ctx.beginPath();
    ctx.moveTo(x+10, y); ctx.lineTo(x-5, y+20); ctx.lineTo(x+5, y+20);
    ctx.lineTo(x-10, y+45); ctx.lineTo(x+15, y+18); ctx.lineTo(x+2, y+18); ctx.closePath();
    ctx.fill(); ctx.restore();
}

function watermark(ctx, w, h) {
    ctx.fillStyle = 'rgba(201,169,110,0.4)'; ctx.font = 'italic 14px serif';
    ctx.textAlign = 'center'; ctx.fillText('SnapStudio', w/2, h-8);
}

function loadImg(src) {
    return new Promise(res => { const i=new Image(); i.onload=()=>res(i); i.src=src; });
}


// ============================================================
// CINEMA GENERATOR
// ============================================================
async function generateCinema(canvas, ctx) {
    const W = 700, H = 1050;
    canvas.width = W; canvas.height = H;

    const themes = {
        'cinema-red':     { bg:'#8B0000', bg2:'#3D0000', curtain:'#CC0000', gold:'#FFD700', text:'#FFD700' },
        'cinema-gold':    { bg:'#8B6914', bg2:'#3D2B00', curtain:'#B8860B', gold:'#FFD700', text:'#FFFACD' },
        'cinema-night':   { bg:'#1a1a3e', bg2:'#0d0d22', curtain:'#2d2d7a', gold:'#FFD700', text:'#E8D5B0' },
        'cinema-vintage': { bg:'#4a3728', bg2:'#2a1f18', curtain:'#6B4226', gold:'#D4A017', text:'#F5DEB3' },
    };
    const t = themes[currentTemplate] || themes['cinema-red'];

    // Background gradient
    const grad = ctx.createLinearGradient(0,0,0,H);
    grad.addColorStop(0, t.bg); grad.addColorStop(1, t.bg2);
    ctx.fillStyle = grad; ctx.fillRect(0,0,W,H);

    // Curtains left
    ctx.fillStyle = t.curtain;
    ctx.beginPath(); ctx.moveTo(0,0); ctx.lineTo(130,0); ctx.lineTo(90,H*0.55); ctx.lineTo(0,H*0.5); ctx.closePath(); ctx.fill();
    // Curtain folds
    for (let y=40; y<H*0.5; y+=50) {
        ctx.strokeStyle = t.bg+'88'; ctx.lineWidth=3;
        ctx.beginPath(); ctx.moveTo(0,y); ctx.quadraticCurveTo(65,y+20,90,y+25); ctx.stroke();
    }
    // Curtains right
    ctx.fillStyle = t.curtain;
    ctx.beginPath(); ctx.moveTo(W,0); ctx.lineTo(W-130,0); ctx.lineTo(W-90,H*0.55); ctx.lineTo(W,H*0.5); ctx.closePath(); ctx.fill();
    for (let y=40; y<H*0.5; y+=50) {
        ctx.strokeStyle = t.bg+'88'; ctx.lineWidth=3;
        ctx.beginPath(); ctx.moveTo(W,y); ctx.quadraticCurveTo(W-65,y+20,W-90,y+25); ctx.stroke();
    }

    // Title banner
    ctx.fillStyle = t.gold+'22'; ctx.fillRect(0, 20, W, 100);
    ctx.strokeStyle = t.gold; ctx.lineWidth = 3;
    ctx.strokeRect(10,10,W-20,120);

    // Marquee lights top
    for (let x=30; x<W-20; x+=35) {
        ctx.fillStyle = t.gold;
        ctx.beginPath(); ctx.arc(x,18,7,0,Math.PI*2); ctx.fill();
        ctx.fillStyle = '#fff8'; ctx.beginPath(); ctx.arc(x,18,4,0,Math.PI*2); ctx.fill();
    }

    ctx.fillStyle = t.text; ctx.textAlign = 'center';
    ctx.font = 'bold 42px Impact, serif'; ctx.fillText('PHOTO BOOTH', W/2, 75);
    ctx.font = 'italic 22px serif'; ctx.fillText('Studio SnapStudio', W/2, 108);

    // 5 photo slots in 2-col grid
    const slots = [
        {x:105,y:155,w:220,h:175},{x:375,y:155,w:220,h:175},
        {x:105,y:360,w:220,h:175},{x:375,y:360,w:220,h:175},
        {x:160,y:565,w:380,h:260},
    ];
    const labels = ['SHOW TIME','','CINEMA','COMING SOON','PREMIERE'];

    for (let i=0; i<5; i++) {
        const s = slots[i];
        // Gold frame with rounded corners
        ctx.strokeStyle = t.gold; ctx.lineWidth = 6;
        ctx.shadowColor = t.gold; ctx.shadowBlur = 10;
        ctx.strokeRect(s.x, s.y, s.w, s.h);
        ctx.shadowBlur = 0;

        // Marquee lights on frame
        const step = 20;
        for (let lx=s.x; lx<s.x+s.w; lx+=step) {
            [s.y-5, s.y+s.h+5].forEach(ly => {
                ctx.fillStyle = (Math.floor((lx-s.x)/step)%2===0) ? t.gold : '#fff';
                ctx.beginPath(); ctx.arc(lx,ly,5,0,Math.PI*2); ctx.fill();
            });
        }
        for (let ly=s.y; ly<s.y+s.h; ly+=step) {
            [s.x-5, s.x+s.w+5].forEach(lx => {
                ctx.fillStyle = (Math.floor((ly-s.y)/step)%2===0) ? t.gold : '#fff';
                ctx.beginPath(); ctx.arc(lx,ly,5,0,Math.PI*2); ctx.fill();
            });
        }

        if (photos[i]) {
            const img = await loadImg(photos[i]);
            ctx.save(); ctx.rect(s.x+3,s.y+3,s.w-6,s.h-6); ctx.clip();
            ctx.drawImage(img,s.x+3,s.y+3,s.w-6,s.h-6); ctx.restore();
        }

        if (labels[i]) {
            ctx.save();
            ctx.font = 'bold 24px Impact';
            ctx.fillStyle = t.gold;
            ctx.strokeStyle = '#000'; ctx.lineWidth = 4;
            if (i===0) { ctx.translate(s.x-10, s.y+60); ctx.rotate(-Math.PI/2); ctx.textAlign='center'; }
            else if (i===3) { ctx.translate(s.x+s.w+10, s.y+80); ctx.rotate(Math.PI/2); ctx.textAlign='center'; }
            else { ctx.textAlign='center'; ctx.translate(s.x+s.w/2, s.y+s.h+30); }
            ctx.strokeText(labels[i],0,0); ctx.fillText(labels[i],0,0);
            ctx.restore();
        }
    }

    // Bottom pedestals
    ctx.fillStyle = t.gold+'50';
    [140,440].forEach(px => {
        ctx.beginPath(); ctx.ellipse(px,H-60,80,25,0,0,Math.PI*2); ctx.fill();
        ctx.fillRect(px-30,H-120,60,60);
    });

    ctx.fillStyle = t.text+'60'; ctx.font='italic 16px serif'; ctx.textAlign='center';
    ctx.fillText('SnapStudio © '+new Date().getFullYear(), W/2, H-20);
}

// ============================================================
// RETRO COLLAGE GENERATOR
// ============================================================
async function generateRetroCollage(canvas, ctx) {
    const W = 900, H = 1300;
    canvas.width = W; canvas.height = H;

    const themes = {
        'retro-warm':  { bg:'#f5e6d0', bg2:'#e8d4b8', text:'#5a3e28', accent:'#c0392b' },
        'retro-cool':  { bg:'#d0e6f5', bg2:'#b8d4e8', text:'#1a3a5a', accent:'#2980b9' },
        'retro-dark':  { bg:'#1a1a1a', bg2:'#0d0d0d', text:'#e8d5b0', accent:'#e74c3c' },
        'retro-pastel':{ bg:'#f0e0ff', bg2:'#e0c8ff', text:'#4a1a6a', accent:'#9b59b6' },
    };
    const t = themes[currentTemplate] || themes['retro-warm'];

    // Background
    const grad = ctx.createLinearGradient(0,0,W,H);
    grad.addColorStop(0,t.bg); grad.addColorStop(1,t.bg2);
    ctx.fillStyle=grad; ctx.fillRect(0,0,W,H);

    // Scattered texture dots
    ctx.fillStyle = t.text+'0A';
    for (let i=0;i<200;i++) {
        ctx.beginPath();
        ctx.arc(Math.random()*W, Math.random()*H, Math.random()*4+1,0,Math.PI*2);
        ctx.fill();
    }

    // Vinyl record top-right
    const vx=W-100, vy=80, vr=70;
    ctx.fillStyle='#111'; ctx.beginPath(); ctx.arc(vx,vy,vr,0,Math.PI*2); ctx.fill();
    for (let r=15;r<vr;r+=8) {
        ctx.strokeStyle='#333'; ctx.lineWidth=2;
        ctx.beginPath(); ctx.arc(vx,vy,r,0,Math.PI*2); ctx.stroke();
    }
    ctx.fillStyle=t.accent; ctx.beginPath(); ctx.arc(vx,vy,18,0,Math.PI*2); ctx.fill();
    ctx.fillStyle='#111'; ctx.beginPath(); ctx.arc(vx,vy,6,0,Math.PI*2); ctx.fill();

    // Cassette tape bottom-left area
    const cx2=120, cy2=H-120;
    ctx.fillStyle=t.accent+'CC';
    ctx.fillRect(cx2-70,cy2-35,140,70);
    ctx.strokeStyle=t.text; ctx.lineWidth=2; ctx.strokeRect(cx2-70,cy2-35,140,70);
    ctx.fillStyle='#000';
    ctx.beginPath(); ctx.arc(cx2-25,cy2,18,0,Math.PI*2); ctx.fill();
    ctx.beginPath(); ctx.arc(cx2+25,cy2,18,0,Math.PI*2); ctx.fill();
    ctx.fillStyle=t.text+'30'; ctx.fillRect(cx2-50,cy2-10,100,20);

    // 6 photos scattered like polaroids
    const layouts6 = [
        {x:30, y:160,w:320,h:260,rot:-5},
        {x:380,y:30, w:290,h:240,rot: 4},
        {x:50, y:450,w:300,h:250,rot: 3},
        {x:370,y:290,w:310,h:255,rot:-3},
        {x:20, y:730,w:340,h:270,rot:-2},
        {x:360,y:560,w:300,h:260,rot: 5},
    ];

    for (let i=0;i<Math.min(photos.length,6);i++) {
        const l=layouts6[i];
        const img=await loadImg(photos[i]);
        ctx.save();
        ctx.translate(l.x+l.w/2+14, l.y+l.h/2+50);
        ctx.rotate(l.rot*Math.PI/180);
        // Shadow
        ctx.shadowColor='rgba(0,0,0,0.35)'; ctx.shadowBlur=16; ctx.shadowOffsetX=5; ctx.shadowOffsetY=8;
        // Polaroid white frame
        ctx.fillStyle='#FFFFFF'; ctx.fillRect(-l.w/2-14,-l.h/2-14,l.w+28,l.h+70);
        ctx.shadowColor='transparent';
        ctx.drawImage(img,-l.w/2,-l.h/2,l.w,l.h);
        ctx.restore();
    }

    // Camera icon (center top area, subtle)
    ctx.save(); ctx.globalAlpha=0.12;
    ctx.fillStyle=t.text; ctx.font='bold 100px sans-serif'; ctx.textAlign='center';
    ctx.fillText('📷',W/2, 130); ctx.restore();

    // Bottom watermark
    ctx.fillStyle=t.text+'60'; ctx.font='italic bold 22px serif'; ctx.textAlign='center';
    ctx.fillText('SnapStudio', W/2, H-20);
}

// ============================================================
// MUSIC PLAYER GENERATOR
// ============================================================
async function generateMusicPlayer(canvas, ctx) {
    const W = 460, H = 1100;
    canvas.width = W; canvas.height = H;

    const themes = {
        'music-green':  { bg:'#121212', accent:'#1DB954', text:'#FFFFFF', sub:'#B3B3B3' },
        'music-dark':   { bg:'#000000', accent:'#FFFFFF', text:'#FFFFFF', sub:'#999' },
        'music-purple': { bg:'#0d0d1a', accent:'#7B2FBE', text:'#FFFFFF', sub:'#C9A0DC' },
        'music-blue':   { bg:'#0a1628', accent:'#1DB9B9', text:'#FFFFFF', sub:'#87CEEB' },
    };
    const t = themes[currentTemplate] || themes['music-green'];
    const songTitle  = document.getElementById('music-title')?.value  || 'Your Favorite Playlist';
    const songArtist = document.getElementById('music-artist')?.value || 'SnapStudio';

    // Get current time for display
    const now = new Date();
    const timeStr = now.getHours().toString().padStart(2,'0') + '.' + now.getMinutes().toString().padStart(2,'0');
    const dateStr = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][now.getDay()] + ', ' +
        now.getDate() + ' ' + ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][now.getMonth()] + ' ' + now.getFullYear();

    // Background gradient
    const grad = ctx.createLinearGradient(0,0,0,H);
    grad.addColorStop(0, t.accent+'44'); grad.addColorStop(0.4, t.bg); grad.addColorStop(1, t.bg);
    ctx.fillStyle=grad; ctx.fillRect(0,0,W,H);

    // Header: date + time
    ctx.fillStyle=t.text; ctx.textAlign='center';
    ctx.font=`500 18px 'Inter', sans-serif`; ctx.fillText(dateStr, W/2, 44);
    ctx.font=`bold 80px 'Inter', sans-serif`; ctx.fillStyle=t.text; ctx.fillText(timeStr, W/2, 130);

    // 3 photo slots with rounded corners
    const photoH = 215, photoW = W-60, photoX = 30;
    for (let i=0;i<3;i++) {
        const py = 155 + i*(photoH+12);
        // Rounded rect photo frame
        ctx.beginPath();
        roundRect(ctx, photoX, py, photoW, photoH, 20);
        ctx.fillStyle = t.accent+'30'; ctx.fill();
        ctx.strokeStyle = t.accent+'60'; ctx.lineWidth=2; ctx.stroke();

        if (photos[i]) {
            const img=await loadImg(photos[i]);
            ctx.save();
            ctx.beginPath(); roundRect(ctx, photoX+2, py+2, photoW-4, photoH-4, 18);
            ctx.clip();
            ctx.drawImage(img, photoX+2, py+2, photoW-4, photoH-4);
            ctx.restore();
        }
    }

    // Music info section
    const infoY = 155 + 3*(photoH+12) + 20;
    // Heart icon
    ctx.fillStyle='#E74C3C'; ctx.font='24px sans-serif'; ctx.textAlign='right';
    ctx.fillText('♥', W-30, infoY+28);

    ctx.textAlign='left';
    ctx.fillStyle=t.text; ctx.font=`bold 22px 'Inter', sans-serif`;
    ctx.fillText(songTitle, 30, infoY+28);
    ctx.fillStyle=t.sub; ctx.font=`16px 'Inter', sans-serif`;
    ctx.fillText(songArtist, 30, infoY+52);

    // Progress bar
    const barY=infoY+70, barW=W-60;
    ctx.fillStyle=t.sub+'40'; ctx.beginPath(); roundRect(ctx,30,barY,barW,4,2); ctx.fill();
    ctx.fillStyle=t.accent; ctx.beginPath(); roundRect(ctx,30,barY,barW*0.38,4,2); ctx.fill();
    ctx.fillStyle=t.text; ctx.fillText('1:42', 30, barY+20);
    ctx.textAlign='right'; ctx.fillText('4:15', W-30, barY+20); ctx.textAlign='left';

    // Controls row
    const ctrlY=infoY+110;
    const controls = ['⇄','⏮','⏵','⏭','↺'];
    controls.forEach((c,i)=>{
        ctx.fillStyle = i===2 ? t.accent : t.text;
        ctx.font = i===2 ? `bold 38px sans-serif` : `24px sans-serif`;
        ctx.textAlign='center';
        ctx.fillText(c, 50+i*(W-60)/4, ctrlY+(i===2?4:0));
    });

    // Studio label bottom
    const labelY = ctrlY+44;
    ctx.fillStyle=t.text+'CC'; ctx.font=`bold 13px 'Inter', sans-serif`;
    ctx.textAlign='center'; ctx.fillText('LARANA PHOTOBOX', W/2, labelY);
    ctx.fillStyle=t.sub; ctx.font=`11px 'Inter', sans-serif`;
    ctx.fillText('SnapStudio', W/2, labelY+18);
}

function roundRect(ctx, x, y, w, h, r) {
    ctx.beginPath();
    ctx.moveTo(x+r,y); ctx.lineTo(x+w-r,y);
    ctx.quadraticCurveTo(x+w,y,x+w,y+r);
    ctx.lineTo(x+w,y+h-r); ctx.quadraticCurveTo(x+w,y+h,x+w-r,y+h);
    ctx.lineTo(x+r,y+h); ctx.quadraticCurveTo(x,y+h,x,y+h-r);
    ctx.lineTo(x,y+r); ctx.quadraticCurveTo(x,y,x+r,y);
    ctx.closePath();
}

// ============================================================
// UPLOAD
// ============================================================
async function uploadPhotos() {
    if (!photos.length) return;
    const btn = event.target; btn.textContent='Menyimpan...'; btn.disabled=true;
    try {
        const r = await fetch('{{ route("photos.store") }}', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({ photos, filter:currentFilter, frame_id:currentFrameId })
        });
        if (r.ok) { btn.textContent='✓ Tersimpan!'; setTimeout(()=>window.location.href='{{ route("gallery") }}',1200); }
        else throw new Error();
    } catch { btn.textContent='Coba Lagi'; btn.disabled=false; }
}

// ============================================================
// INIT
// ============================================================
window.addEventListener('load', () => {
    startCamera();
    setMode('classic');
});
</script>
@endpush
