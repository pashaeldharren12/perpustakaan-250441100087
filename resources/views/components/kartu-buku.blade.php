@props(['judul', 'penulis', 'tahun', 'url'])

<div class="card">
    <h3 class="card-title">{{ $judul }}</h3>
    <p class="card-meta">Penulis: {{ $penulis }}</p>
    <p class="card-meta">Tahun Terbit: {{ $tahun }}</p>

    {{-- Slot default: bagian tambahan (mis. kategori) --}}
    @if (!$slot->isEmpty())
        <div class="card-extra">{{ $slot }}</div>
    @endif

    <a href="{{ $url }}" class="btn btn-sm">Lihat Detail</a>

    {{-- Named slot (opsional) --}}
    @isset($footer)
        <div class="card-footer">{{ $footer }}</div>
    @endisset
</div>