<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Management System')</title>
    <link rel="stylesheet" href="{{ asset('css/y2k.css') }}">
    <script>
        try { document.documentElement.dataset.theme = localStorage.getItem('theme') || 'coral'; } catch (e) {}
    </script>
    @hasSection('intro')
        <script>
            try {
                const hold = new URLSearchParams(location.search).get('intro') === 'hold';
                if (hold || !sessionStorage.getItem('intro')) document.documentElement.classList.add('intro-active');
            } catch (e) {}
        </script>
    @endif
</head>
<body>
@yield('intro')

<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="brand">🎓 SIM Mahasiswa</div>
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('mahasiswa.index') }}" class="{{ request()->routeIs('mahasiswa.index', 'mahasiswa.show', 'mahasiswa.edit') ? 'active' : '' }}">Data Mahasiswa</a>
            <a href="{{ route('mahasiswa.create') }}" class="{{ request()->routeIs('mahasiswa.create') ? 'active' : '' }}">Tambah Mahasiswa</a>
        </nav>
        <div class="side-foot">
            <a href="{{ route('dashboard') }}" class="btn btn-sm" onclick="try{sessionStorage.removeItem('intro')}catch(e){}">▶ Putar Intro</a>
            <button type="button" class="btn btn-sm" id="themeBtn">Ganti Tema</button>
            <div class="clock" id="clock"></div>
        </div>
    </aside>
    <div class="overlay" id="overlay"></div>

    <main class="main">
        <div class="topbar">
            <button type="button" class="btn btn-sm" id="menuBtn">☰ Menu</button>
            <span>SIM Mahasiswa</span>
        </div>

        <div class="win">
            <div class="titlebar">
                <span>@yield('title', 'Mahasiswa.exe')</span>
                <span class="ctrl"><span>_</span><span>□</span><span>×</span></span>
            </div>
            <div class="win-body">
                @if (session('success'))
                    <div class="alert" data-autodismiss>
                        <span>✔ {{ session('success') }}</span>
                        <button type="button" data-close>×</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error">
                        <span>✖ {{ session('error') }}</span>
                        <button type="button" data-close>×</button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">
                        <span>✖ Terdapat {{ $errors->count() }} kesalahan pada form. Periksa kembali isian kamu.</span>
                        <button type="button" data-close>×</button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>
</div>

{{-- Modal konfirmasi hapus --}}
<div class="modal" id="deleteModal" hidden>
    <div class="win">
        <div class="titlebar"><span>Konfirmasi Hapus</span><span class="ctrl"><span>×</span></span></div>
        <div class="win-body">
            <p>Yakin ingin menghapus data <b id="deleteName"></b>? Tindakan ini tidak bisa dibatalkan.</p>
            <form id="deleteForm" method="POST" data-loading>
                @csrf @method('DELETE')
                <div class="btn-group">
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    <button type="button" class="btn" id="deleteCancel">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tema
    const themes = ['coral', 'lavender', 'mint', 'sky'];
    document.getElementById('themeBtn').addEventListener('click', () => {
        const now = document.documentElement.dataset.theme;
        const next = themes[(themes.indexOf(now) + 1) % themes.length];
        document.documentElement.dataset.theme = next;
        try { localStorage.setItem('theme', next); } catch (e) {}
    });

    // Jam
    function tick() {
        document.getElementById('clock').textContent =
            new Date().toLocaleString('id-ID', { weekday: 'long', hour: '2-digit', minute: '2-digit' });
    }
    tick(); setInterval(tick, 1000);

    // Sidebar mobile
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    function toggleMenu(open) {
        sidebar.classList.toggle('open', open);
        overlay.classList.toggle('show', open);
    }
    document.getElementById('menuBtn').addEventListener('click', () => toggleMenu(true));
    overlay.addEventListener('click', () => toggleMenu(false));

    // Modal hapus
    const modal = document.getElementById('deleteModal');
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-delete]');
        if (btn) {
            document.getElementById('deleteForm').action = btn.dataset.delete;
            document.getElementById('deleteName').textContent = btn.dataset.name;
            modal.hidden = false;
        }
        if (e.target === modal || e.target.id === 'deleteCancel') modal.hidden = true;
        if (e.target.matches('[data-close]')) e.target.closest('.alert').remove();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') modal.hidden = true; });

    // Loading state saat submit
    document.querySelectorAll('form[data-loading]').forEach((form) => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type=submit]');
            if (btn) { btn.classList.add('loading'); btn.textContent = 'Memproses...'; }
        });
    });

    // Notifikasi sukses hilang otomatis
    document.querySelectorAll('[data-autodismiss]').forEach((el) => setTimeout(() => el.remove(), 4000));
</script>

<script>
(function () {
    const html   = document.documentElement;
    const intro  = document.getElementById('intro');
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const hold   = new URLSearchParams(location.search).get('intro') === 'hold';

    // Indeks untuk animasi bertahap
    document.querySelectorAll('.stats .stat').forEach((el, i) => el.style.setProperty('--i', i));
    document.querySelectorAll('.prodi-row').forEach((el, i) => el.style.setProperty('--i', i));
    document.querySelectorAll('tbody tr').forEach((el, i) => el.style.setProperty('--i', Math.min(i, 12)));

    // Sapaan sesuai jam
    const greet = document.getElementById('greet');
    if (greet) {
        const h = new Date().getHours();
        greet.textContent = h < 11 ? 'Selamat pagi' : h < 15 ? 'Selamat siang' : h < 18 ? 'Selamat sore' : 'Selamat malam';
    }

    // Angka menghitung dari 0
    function countUp() {
        if (reduce) return;
        document.querySelectorAll('[data-count]').forEach((el) => {
            const end = parseInt(el.dataset.count, 10) || 0;
            if (end === 0) return;
            const start = performance.now() + 300, dur = 900;
            el.textContent = '0';
            (function step(now) {
                const p = Math.min(Math.max((now - start) / dur, 0), 1);
                el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(step);
            })(performance.now());
        });
    }

    // Tanpa intro: langsung animasikan isi halaman
    if (!intro || !html.classList.contains('intro-active')) {
        countUp();
        return;
    }

    // Dengan intro
    const status = document.getElementById('introStatus');
    const msgs = [
        [0,    'Memuat sistem...'],
        [700,  'Menyiapkan database...'],
        [1400, 'Memuat data mahasiswa...'],
        [2150, 'Siap! 🎉'],
    ];
    const timers = msgs.map(([t, text]) => setTimeout(() => status.textContent = text, t));

    let done = false;
    function finish() {
        if (done) return;
        done = true;
        timers.forEach(clearTimeout);
        try { sessionStorage.setItem('intro', '1'); } catch (e) {}
        intro.classList.add('out');
        html.classList.remove('intro-active');   // animasi dashboard mulai
        countUp();
        setTimeout(() => intro.remove(), 600);
    }

    if (hold) {
        // Mode screenshot: intro tetap tampil sampai klik dua kali atau tekan Esc
        const skip = intro.querySelector('.intro-skip');
        if (skip) skip.style.visibility = 'hidden';
        intro.addEventListener('dblclick', finish);
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') finish(); });
    } else {
        const auto = setTimeout(finish, reduce ? 0 : 2800);
        intro.addEventListener('click', () => { clearTimeout(auto); finish(); });
        document.addEventListener('keydown', () => { clearTimeout(auto); finish(); }, { once: true });
    }
})();
</script>
</body>
</html>