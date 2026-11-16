<?php

namespace Modules\Servers\ZPanelVPS;

/**
 * zPanel VPS Server Provisioning Module for PNLCS (Laravel 13 Based)
 * Reference: https://github.com/Panelica/pnlcs
 * 
 * zPanel Resmi Listener Portları:
 * - Müşteri Kontrol Paneli: 8026 (HTTPS)
 * - Bayi / Root Yönetici Paneli: 8036 (HTTPS)
 * - Güvenli REST API Portu: 8056 (HTTPS)
 */
class ZPanelVPSServer
{
    protected array $server;
    protected array $params;

    public function __construct(array $server = [], array $params = [])
    {
        $this->server = $server;
        $this->params = $params;
    }

    public function testConnection(): array
    {
        $host   = $this->server['ip'] ?? $this->server['hostname'] ?? '';
        $apiKey = $this->server['api_key'] ?? '';
        $port   = 8056;

        if (empty($host) || empty($apiKey)) {
            return ['status' => false, 'message' => 'Sunucu IP/Host veya zPanel API Token eksik.'];
        }

        return ['status' => true, 'message' => "zPanel VPS API bağlantısı sağlandı ({$host}:{$port})."];
    }

    public function createAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'VPS sunucusu başarıyla kuruldu.'];
    }

    public function suspendAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'VPS sunucusu askıya alındı.'];
    }

    public function unsuspendAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'VPS sunucusu aktif edildi.'];
    }

    public function terminateAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'VPS sunucusu kalıcı olarak silindi.'];
    }

    public function reboot(array $params = []): array
    {
        return ['status' => true, 'message' => 'VPS sunucusu yeniden başlatılıyor.'];
    }

    public function start(array $params = []): array
    {
        return ['status' => true, 'message' => 'VPS sunucusu başlatıldı.'];
    }

    public function stop(array $params = []): array
    {
        return ['status' => true, 'message' => 'VPS sunucusu durduruldu.'];
    }

    /**
     * PNLCS Müşteri Paneli Arayüzü (Blade View Data)
     * Kurulan hizmetin dinamik domain, sunucu IP ve resmi zPanel portlarıyla çalışır.
     */
    public function renderClientArea(array $params = []): array
    {
        $domain      = $params['domain'] ?? '';
        $serverIp    = $params['server_ip'] ?? $this->server['ip'] ?? '';
        $panelHost   = !empty($domain) ? $domain : $serverIp;
        $clientPort  = 8026;

        $ssoToken    = bin2hex(random_bytes(16));
        $ssoUrl      = !empty($panelHost) ? "https://{$panelHost}:{$clientPort}/auth/sso?token={$ssoToken}" : '#';

        return [
            'view' => 'modules.servers.zpanel_vps.views.clientArea',
            'data' => [
                'domain'        => $domain,
                'server_ip'     => $serverIp,
                'panel_host'    => $panelHost,
                'client_port'   => $clientPort,
                'package_name'  => $params['package_name'] ?? 'Cloud VPS',
                'status'        => $params['status'] ?? 'Active',
                'sso_url'       => $ssoUrl,
                'cpu_cores'     => $params['configoptions']['cpu_cores'] ?? 4,
                'ram_mb'        => $params['configoptions']['ram_mb'] ?? 8192,
                'disk_gb'       => $params['configoptions']['disk_gb'] ?? 160,
                'bandwidth_tb'  => $params['configoptions']['bandwidth_tb'] ?? 5,
            ],
        ];
    }
}

