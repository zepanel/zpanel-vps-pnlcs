{{-- zPanel VPS PNLCS Blade View --}}
{{-- Port Mimarisi: 8026 (Client), 8036 (Root), 8046 (Webmail), 8056 (API) --}}

<link rel="stylesheet" href="/modules/Servers/ZPanelVPS/assets/css/client.css">

<div class="zp-pnlcs-vps">
    <!-- Üst Başlık Kartı -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl font-bold text-slate-900">{{ $package_name ?? 'zPanel Bulut Sunucu' }}</h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        {{ $status }}
                    </span>
                </div>
                <div class="text-sm font-mono text-slate-500">
                    @if(!empty($domain))
                        <a href="https://{{ $domain }}:{{ $client_port ?? 8026 }}" target="_blank" class="text-blue-600 hover:underline font-bold">
                            {{ $domain }}:{{ $client_port ?? 8026 }}
                        </a>
                        &bull;
                    @endif
                    Sunucu IP: <span class="font-bold text-slate-900">{{ $server_ip ?: 'Atanmadı' }}</span>
                    &bull;
                    İşletim Sistemi: <span class="text-slate-800 font-sans font-semibold">{{ $os_name ?? 'Ubuntu / AlmaLinux' }}</span>
                </div>
            </div>
            <div>
                <a href="{{ $sso_url }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-blue-700 transition">
                    zPanel Giriş (:{{ $client_port ?? 8026 }}) &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Makine Donanım Özellikleri Kartları -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">İşlemci Kapasitesi</div>
            <div class="text-2xl font-black text-slate-900">{{ $cpu_cores ?? 4 }} <span class="text-sm font-semibold text-slate-500">vCPU</span></div>
            <div class="text-xs text-slate-400 mt-1">Yüksek Performans Çekirdek</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Bellek (RAM)</div>
            <div class="text-2xl font-black text-slate-900">{{ $ram_mb ?? 8192 }} <span class="text-sm font-semibold text-slate-500">MB</span></div>
            <div class="text-xs text-slate-400 mt-1">DDR4 / DDR5 ECC RAM</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Depolama Alanı</div>
            <div class="text-2xl font-black text-slate-900">{{ $disk_gb ?? 160 }} <span class="text-sm font-semibold text-slate-500">GB NVMe</span></div>
            <div class="text-xs text-slate-400 mt-1">RAID10 NVMe SSD Depolama</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Aylık Trafik Kotası</div>
            <div class="text-2xl font-black text-slate-900">{{ $bandwidth_tb ?? 5 }} <span class="text-sm font-semibold text-slate-500">TB</span></div>
            <div class="text-xs text-slate-400 mt-1">1 Gbps Korumalı Hat</div>
        </div>
    </div>

    <!-- Gerçek Sunucu Eylemleri Butonları -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 mb-4">Sunucu Yönetim Eylemleri</h3>
        <div class="flex gap-3 flex-wrap">
            <form method="POST" action="" onsubmit="return confirm('Sunucuyu yeniden başlatmak istediğinize emin misiniz?');">
                @csrf
                <input type="hidden" name="action" value="reboot">
                <button type="submit" class="inline-flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-100 transition">
                    <span>&#x21bb;</span> Sunucuyu Yeniden Başlat
                </button>
            </form>

            <form method="POST" action="" onsubmit="return confirm('zPanel servislerini yeniden başlatmak istediğinize emin misiniz?');">
                @csrf
                <input type="hidden" name="action" value="restartPanel">
                <button type="submit" class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 border border-slate-300 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-200 transition">
                    <span>&#x2699;</span> zPanel Servislerini Yeniden Başlat
                </button>
            </form>

            <a href="https://{{ $panel_host ?? $server_ip }}:8046" target="_blank" class="inline-flex items-center gap-2 bg-slate-50 text-slate-900 border border-slate-300 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-100 transition">
                <span>&#x2709;</span> Webmail Giriş (:8046)
            </a>
        </div>
    </div>

    <!-- Ağ ve Port Detayları Tablosu -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 mb-4">Sunucu Ağ &amp; Port Yapılandırması</h3>
        <table class="w-full text-sm text-left">
            <tr class="border-b border-slate-100"><td class="py-2.5 text-slate-500">Statik IPv4 Adresi</td><td class="py-2.5 font-mono font-bold text-slate-900">{{ $server_ip ?: 'Atanmadı' }}</td></tr>
            <tr class="border-b border-slate-100"><td class="py-2.5 text-slate-500">Web Sunucusu</td><td class="py-2.5 font-semibold text-slate-800">zMaxServer &amp; OpenLiteSpeed (LSAPI)</td></tr>
            <tr class="border-b border-slate-100"><td class="py-2.5 text-slate-500">zPanel Müşteri Paneli</td><td class="py-2.5 font-mono text-blue-600 font-bold">8026 / TCP (HTTPS)</td></tr>
            <tr class="border-b border-slate-100"><td class="py-2.5 text-slate-500">zPanel Root / Yönetici Paneli</td><td class="py-2.5 font-mono text-amber-600 font-bold">8036 / TCP (HTTPS)</td></tr>
            <tr class="border-b border-slate-100"><td class="py-2.5 text-slate-500">Webmail Portu</td><td class="py-2.5 font-mono text-slate-900 font-bold">8046 / TCP (HTTPS)</td></tr>
            <tr class="border-b border-slate-100"><td class="py-2.5 text-slate-500">Core REST API Portu</td><td class="py-2.5 font-mono text-slate-900 font-bold">8056 / TCP (HTTPS)</td></tr>
        </table>
    </div>
</div>
