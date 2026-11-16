{{-- zPanel VPS PNLCS Blade View --}}
<div class="zp-pnlcs-vps">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl font-bold text-slate-900">{{ $package_name }}</h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ $status }}
                    </span>
                </div>
                <div class="text-sm font-mono text-slate-500">
                    @if(!empty($domain))
                        <a href="https://{{ $domain }}:{{ $client_port }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $domain }}:{{ $client_port }}
                        </a>
                        &bull;
                    @endif
                    Sunucu IP: <span class="font-bold text-slate-700">{{ $server_ip ?: 'Atanmadı' }}</span>
                </div>
            </div>
            <div>
                <a href="{{ $sso_url }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-900 text-white font-semibold px-5 py-2.5 rounded-lg hover:bg-slate-800 transition">
                    zPanel Giriş (:{{ $client_port }}) &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- 3 Gauge Donanım İbreleri --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">CPU Kullanımı</div>
            <div class="text-2xl font-black text-slate-900">12%</div>
            <div class="text-xs text-slate-400 mt-1">{{ $cpu_cores }} vCPU Çekirdek</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Bellek (RAM)</div>
            <div class="text-2xl font-black text-slate-900">28%</div>
            <div class="text-xs text-slate-400 mt-1">2.2 GB / {{ round($ram_mb / 1024, 1) }} GB</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-center shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Depolama (NVMe)</div>
            <div class="text-2xl font-black text-slate-900">15%</div>
            <div class="text-xs text-slate-400 mt-1">24 GB / {{ $disk_gb }} GB NVMe</div>
        </div>
    </div>

    {{-- 8 Hızlı İşlem Butonları --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <button type="button" class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition flex flex-col items-center gap-2">
            <span class="text-xl">🔄</span>
            <span class="text-sm">Yeniden Başlat</span>
        </button>
        <button type="button" class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition flex flex-col items-center gap-2">
            <span class="text-xl">▶️</span>
            <span class="text-sm">Başlat</span>
        </button>
        <button type="button" class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition flex flex-col items-center gap-2">
            <span class="text-xl">⏹️</span>
            <span class="text-sm">Kapat</span>
        </button>
        <button type="button" class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition flex flex-col items-center gap-2">
            <span class="text-xl">🖥️</span>
            <span class="text-sm">VNC Konsol</span>
        </button>
        <button type="button" class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition flex flex-col items-center gap-2">
            <span class="text-xl">🔑</span>
            <span class="text-sm">Root Şifresi</span>
        </button>
        <button type="button" class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition flex flex-col items-center gap-2">
            <span class="text-xl">⚡</span>
            <span class="text-sm">Yeniden Kur</span>
        </button>
        <button type="button" class="p-4 bg-white border border-slate-200 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition flex flex-col items-center gap-2">
            <span class="text-xl">💾</span>
            <span class="text-sm">Snapshot</span>
        </button>
        <a href="{{ $sso_url }}" target="_blank" class="p-4 bg-slate-900 border border-slate-900 rounded-lg text-white font-semibold hover:bg-slate-800 transition flex flex-col items-center gap-2">
            <span class="text-xl">🌐</span>
            <span class="text-sm">zPanel SSO</span>
        </a>
    </div>
</div>

