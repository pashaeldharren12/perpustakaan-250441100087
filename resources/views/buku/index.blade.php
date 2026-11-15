@extends('layouts.perpus')

@section('title', 'Daftar Buku')

@section('content')
    <style>
        .alert { padding: .7rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: .95rem; }
        .alert[hidden] { display: none; }
        .alert-success { background: #e6f6ec; color: #1b6b3a; border: 1px solid #b7e2c6; }
        .alert-error { background: #fdecea; color: #a12622; border: 1px solid #f5c2be; }

        .form-card { background: #fff; padding: 1.4rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); margin-bottom: 1.5rem; }
        .form-card h3 { color: var(--primary); margin-bottom: 1rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
        .field label { display: block; font-weight: 600; font-size: .9rem; margin-bottom: .25rem; }
        .field select, .field input {
            width: 100%; padding: .5rem .6rem; border: 1px solid #cfd6df; border-radius: 6px; font: inherit; background: #fff;
        }
        .field.invalid select, .field.invalid input { border-color: #c0392b; }
        .field-error { display: block; color: #c0392b; font-size: .82rem; margin-top: .2rem; min-height: 1em; }
        .form-actions { margin-top: 1rem; display: flex; gap: .6rem; }
        button.btn { border: 0; cursor: pointer; font: inherit; font-weight: 600; }
        button.btn:disabled { opacity: .6; cursor: not-allowed; }
        .btn-secondary { background: #e5e9ef; color: #1a1a1a; }
        .btn-danger { background: #c0392b; color: #fff; }

        .table-wrap { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); overflow-x: auto; }
        table.tabel-buku { width: 100%; border-collapse: collapse; }
        .tabel-buku th, .tabel-buku td { padding: .7rem .9rem; text-align: left; border-bottom: 1px solid #eef1f5; }
        .tabel-buku th { background: var(--primary); color: #fff; font-weight: 600; font-size: .9rem; }
        .tabel-buku tr:last-child td { border-bottom: 0; }
        .tabel-buku .aksi { white-space: nowrap; display: flex; gap: .4rem; }
        .tabel-buku .status-row { text-align: center; color: var(--muted); padding: 1.6rem; }
        .tabel-buku .status-row.error { color: #a12622; }
        .tabel-buku a.judul-link { color: var(--primary); font-weight: 600; text-decoration: none; }
        .tabel-buku a.judul-link:hover { text-decoration: underline; }
        tr.sedang-diubah td { background: #fff8e5; }

        .paginasi { display: flex; align-items: center; justify-content: center; gap: 1rem; margin-top: 1rem; }
        .paginasi .info { color: var(--muted); font-size: .9rem; }
    </style>

    <h2 class="page-title">Daftar Buku</h2>

    <div class="table-wrap" style="padding:1rem;margin-bottom:1rem">
    <strong>Judul Terbaru (dirender server)</strong>
    <ul>
        @foreach ($daftarJudul as $b)
            <li>{!! $b->judul !!}</li>
        @endforeach
    </ul>
</div>

    {{-- Pesan sukses / gagal --}}
    <div id="pesan" class="alert" role="alert" hidden></div>

    {{-- Formulir tambah / ubah: hanya untuk yang berhak kelola buku --}}
    @can('kelola-data-buku')
    <section class="form-card">
        <h3 id="form-judul">Tambah Buku</h3>

        <form id="form-buku" novalidate>
            <div class="form-grid">
                <div class="field" data-field="kategori_id">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id">
                        <option value="">-- Pilih kategori --</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    <span class="field-error" data-error="kategori_id"></span>
                </div>

                <div class="field" data-field="judul">
                    <label for="judul">Judul</label>
                    <input type="text" id="judul" name="judul" placeholder="Judul buku">
                    <span class="field-error" data-error="judul"></span>
                </div>

                <div class="field" data-field="penulis">
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" placeholder="Nama penulis">
                    <span class="field-error" data-error="penulis"></span>
                </div>

                <div class="field" data-field="tahun_terbit">
                    <label for="tahun_terbit">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" placeholder="mis. 2024">
                    <span class="field-error" data-error="tahun_terbit"></span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="btn-simpan" class="btn">Tambah</button>
                <button type="button" id="btn-batal" class="btn btn-secondary" hidden>Batal</button>
            </div>
        </form>
    </section>
    @endcan

    {{-- Tabel: diisi oleh JavaScript dari GET /api/buku --}}
    <div class="table-wrap">
        <table class="tabel-buku">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tabel-body">
                <tr><td colspan="6" class="status-row">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>

    <div class="paginasi">
        <button type="button" id="btn-prev" class="btn btn-sm btn-secondary">&larr; Sebelumnya</button>
        <span id="info-halaman" class="info"></span>
        <button type="button" id="btn-next" class="btn btn-sm btn-secondary">Berikutnya &rarr;</button>
    </div>

    
    <script>
        const API_BUKU = '/api/buku';
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const BISA_KELOLA = @json(auth()->user()->can('kelola-data-buku'));
        const BISA_PINJAM = @json(auth()->user()->can('mengajukan-peminjaman-buku'));
        const URL_DETAIL = '/buku/';

        const state = { page: 1, lastPage: 1, editingId: null, bukus: [] };

        const el = {
            pesan: document.getElementById('pesan'),
            form: document.getElementById('form-buku'),
            formJudul: document.getElementById('form-judul'),
            btnSimpan: document.getElementById('btn-simpan'),
            btnBatal: document.getElementById('btn-batal'),
            tbody: document.getElementById('tabel-body'),
            btnPrev: document.getElementById('btn-prev'),
            btnNext: document.getElementById('btn-next'),
            info: document.getElementById('info-halaman'),
            kategori: document.getElementById('kategori_id'),
            judul: document.getElementById('judul'),
            penulis: document.getElementById('penulis'),
            tahun: document.getElementById('tahun_terbit'),
        };

        /* ---------- Error khusus API ---------- */
        class ApiError extends Error {
            constructor(message, status = 0, errors = null) {
                super(message);
                this.status = status;
                this.errors = errors;
            }
        }

        /**
         * Pembungkus fetch: memeriksa response.ok, membaca JSON,
         * dan melempar ApiError jika gagal (termasuk API tidak dapat diakses).
         */
        async function apiRequest(url, options = {}) {
            let response;

            try {
                response = await fetch(url, {
                    ...options,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json',
                        ...(options.headers || {}),
                    },
                });
            } catch (err) {
                // jaringan putus / server mati
                throw new ApiError('API tidak dapat diakses. Pastikan server berjalan dan koneksi tersedia.');
            }

            let body = null;
            try {
                body = await response.json();
            } catch (err) {
                body = null; // response bukan JSON
            }

            if (!response.ok) {
                let pesan = (body && body.message) || ('Permintaan gagal (HTTP ' + response.status + ').');

                if (response.status === 422) {
                    pesan = 'Data tidak valid. Periksa kembali isian formulir.';
                } else if (response.status === 404) {
                    pesan = 'Data buku tidak ditemukan. Mungkin sudah dihapus.';
                } else if (response.status >= 500) {
                    pesan = 'Terjadi kesalahan pada server (HTTP ' + response.status + ').';
                }

                throw new ApiError(pesan, response.status, body && body.errors ? body.errors : null);
            }

            return body;
        }

        /* ---------- Pesan & error form ---------- */
        function tampilkanPesan(teks, jenis) {
            el.pesan.textContent = teks;
            el.pesan.className = 'alert alert-' + (jenis === 'sukses' ? 'success' : 'error');
            el.pesan.hidden = false;
        }

        function sembunyikanPesan() {
            el.pesan.hidden = true;
            el.pesan.textContent = '';
        }

        function bersihkanErrorField() {
            document.querySelectorAll('[data-error]').forEach(function (span) { span.textContent = ''; });
            document.querySelectorAll('.field.invalid').forEach(function (f) { f.classList.remove('invalid'); });
        }

        function tampilkanErrorField(errors) {
            Object.keys(errors || {}).forEach(function (nama) {
                const span = document.querySelector('[data-error="' + nama + '"]');
                const field = document.querySelector('[data-field="' + nama + '"]');
                if (span) span.textContent = errors[nama][0];
                if (field) field.classList.add('invalid');
            });
        }

        /* ---------- Render tabel ---------- */
        function barisStatus(teks, adalahError) {
            el.tbody.replaceChildren();
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 6;
            td.className = 'status-row' + (adalahError ? ' error' : '');
            td.textContent = teks;
            tr.appendChild(td);
            el.tbody.appendChild(tr);
        }

        function sel(teks) {
            const td = document.createElement('td');
            td.textContent = teks;
            return td;
        }

        function renderTabel(daftar, mulaiNomor) {
            if (daftar.length === 0) {
                barisStatus('Belum ada data buku.', false);
                return;
            }

            el.tbody.replaceChildren();

            daftar.forEach(function (buku, i) {
                const tr = document.createElement('tr');
                if (buku.id === state.editingId) tr.className = 'sedang-diubah';

                // Atribut dibaca sesuai pemetaan BukuResource: kategori, judul, penulis, tahun
                tr.appendChild(sel(mulaiNomor + i));
                tr.appendChild(sel(buku.kategori ?? '-'));

                const tdJudul = document.createElement('td');
                const link = document.createElement('a');
                link.className = 'judul-link';
                link.href = URL_DETAIL + buku.id;
                link.textContent = buku.judul;
                tdJudul.appendChild(link);
                tr.appendChild(tdJudul);

                tr.appendChild(sel(buku.penulis));
                tr.appendChild(sel(buku.tahun));

                const tdAksi = document.createElement('td');
                tdAksi.className = 'aksi';

                const btnUbah = document.createElement('button');
                btnUbah.type = 'button';
                btnUbah.className = 'btn btn-sm';
                btnUbah.textContent = 'Ubah';
                btnUbah.addEventListener('click', function () { mulaiUbah(buku); });

                const btnHapus = document.createElement('button');
                btnHapus.type = 'button';
                btnHapus.className = 'btn btn-sm btn-danger';
                btnHapus.textContent = 'Hapus';
                btnHapus.addEventListener('click', function () { hapusBuku(buku); });

                if (BISA_KELOLA) {
                    tdAksi.appendChild(btnUbah);
                    tdAksi.appendChild(btnHapus);
                }

                if (BISA_PINJAM) {
                    const formPinjam = document.createElement('form');
                    formPinjam.method = 'POST';
                    formPinjam.action = '/buku/' + buku.id + '/pinjam';
                    const token = document.createElement('input');
                    token.type = 'hidden'; token.name = '_token'; token.value = CSRF;
                    const btnPinjam = document.createElement('button');
                    btnPinjam.type = 'submit';
                    btnPinjam.className = 'btn btn-sm';
                    btnPinjam.textContent = 'Pinjam';
                    formPinjam.appendChild(token);
                    formPinjam.appendChild(btnPinjam);
                    tdAksi.appendChild(formPinjam);
                }
                tr.appendChild(tdAksi);

                el.tbody.appendChild(tr);
            });
        }

        function renderPaginasi(meta) {
            state.page = meta.current_page;
            state.lastPage = meta.last_page;
            el.info.textContent = 'Halaman ' + meta.current_page + ' dari ' + meta.last_page + ' (total ' + meta.total + ' buku)';
            el.btnPrev.disabled = meta.current_page <= 1;
            el.btnNext.disabled = meta.current_page >= meta.last_page;
        }

        /* ---------- READ: GET /api/buku ---------- */
        async function muatBuku(page = state.page) {
            barisStatus('Memuat data...', false);

            try {
                const json = await apiRequest(API_BUKU + '?page=' + page);

                // halaman kosong setelah penghapusan -> mundur satu halaman
                if (json.data.length === 0 && page > 1) {
                    return muatBuku(page - 1);
                }

                state.bukus = json.data;
                renderPaginasi(json.meta);
                renderTabel(json.data, json.meta.from ?? 1);
            } catch (err) {
                barisStatus('Gagal memuat data buku. ' + err.message, true);
                tampilkanPesan('Gagal memuat data: ' + err.message, 'error');
            }
        }

        /* ---------- Mode form ---------- */
        function resetForm() {
            el.form.reset();
            state.editingId = null;
            el.formJudul.textContent = 'Tambah Buku';
            el.btnSimpan.textContent = 'Tambah';
            el.btnBatal.hidden = true;
            bersihkanErrorField();
        }

       async function mulaiUbah(ringkas) {
    sembunyikanPesan();
    bersihkanErrorField();

    try {
        const json = await apiRequest(API_BUKU + '/' + ringkas.id);   // GET /api/buku/{id}
        const buku = json.data;

        state.editingId = buku.id;
        el.kategori.value = buku.kategori_id;
        el.judul.value = buku.judul;
        el.penulis.value = buku.penulis;
        el.tahun.value = buku.tahun;

        el.formJudul.textContent = 'Ubah Buku (ID ' + buku.id + ')';
        el.btnSimpan.textContent = 'Simpan Perubahan';
        el.btnBatal.hidden = false;

        renderTabel(state.bukus, (state.page - 1) * 10 + 1);
        el.form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        el.judul.focus();
    } catch (err) {
        tampilkanPesan('Gagal mengambil data buku: ' + err.message, 'error');
        if (err.status === 404) muatBuku(state.page);
    }
}

        function batalUbah() {
            resetForm();
            sembunyikanPesan();
            renderTabel(state.bukus, (state.page - 1) * 10 + 1);
        }

        /* ---------- CREATE / UPDATE: POST & PUT ---------- */
        async function kirimForm(event) {
            event.preventDefault();
            sembunyikanPesan();
            bersihkanErrorField();

            const sedangUbah = state.editingId !== null;

            const payload = {
                kategori_id: el.kategori.value === '' ? null : Number(el.kategori.value),
                judul: el.judul.value,
                penulis: el.penulis.value,
                tahun_terbit: el.tahun.value === '' ? null : Number(el.tahun.value),
            };

            const url = sedangUbah ? API_BUKU + '/' + state.editingId : API_BUKU;
            const method = sedangUbah ? 'PUT' : 'POST';

            el.btnSimpan.disabled = true;

            try {
                const json = await apiRequest(url, { method: method, body: JSON.stringify(payload) });

                // sampai sini berarti response.ok = true
                tampilkanPesan(json.message || 'Data berhasil disimpan.', 'sukses');
                resetForm();
                await muatBuku(sedangUbah ? state.page : 1);
            } catch (err) {
                tampilkanErrorField(err.errors);
                tampilkanPesan(
                    (sedangUbah ? 'Gagal mengubah buku: ' : 'Gagal menambah buku: ') + err.message,
                    'error'
                );
                if (err.status === 404) {
                    resetForm();
                    muatBuku(state.page);
                }
            } finally {
                el.btnSimpan.disabled = false;
            }
        }

        /* ---------- DELETE: DELETE /api/buku/{id} ---------- */
        async function hapusBuku(buku) {
            if (!confirm('Yakin ingin menghapus buku "' + buku.judul + '"?')) {
                return; // dibatalkan -> tidak ada request
            }

            sembunyikanPesan();

            try {
                const json = await apiRequest(API_BUKU + '/' + buku.id, { method: 'DELETE' });

                tampilkanPesan(json.message || 'Buku berhasil dihapus.', 'sukses');
                if (state.editingId === buku.id) resetForm();
                await muatBuku(state.page);
            } catch (err) {
                tampilkanPesan('Gagal menghapus buku: ' + err.message, 'error');
                if (err.status === 404) muatBuku(state.page);
            }
        }

        /* ---------- Event ---------- */
        if (el.form) el.form.addEventListener('submit', kirimForm);
        if (el.btnBatal) el.btnBatal.addEventListener('click', batalUbah);
        el.btnPrev.addEventListener('click', function () { muatBuku(state.page - 1); });
        el.btnNext.addEventListener('click', function () { muatBuku(state.page + 1); });

        muatBuku(1);
    </script>
@endsection
