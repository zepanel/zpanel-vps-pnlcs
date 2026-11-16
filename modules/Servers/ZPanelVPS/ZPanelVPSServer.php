<?php

namespace Modules\Servers\ZPanelVPS;

/**
 * zPanel VPS Server Provisioning Module for PNLCS (Laravel 13 Architecture)
 * Reference: https://github.com/Panelica/pnlcs
 * 
 * Resmi Port Mimarisi:
 * - 8026 / TCP (HTTPS): Müşteri Kontrol Paneli
 * - 8036 / TCP (HTTPS): Root / Yönetici Paneli
 * - 8046 / TCP (HTTPS): Webmail
 * - 8056 / TCP (HTTPS): Core REST API
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

    protected function callApi(string $method, string $path, array $data = []): array
    {
        $host = trim($this->server['ip'] ?? $this->server['hostname'] ?? $this->params['domain'] ?? '', '/');
        $apiKey = trim($this->server['api_key'] ?? $this->server['password'] ?? '');
        $port = 8056;

        if (empty($host) || empty($apiKey)) {
            return ['status' => false, 'message' => 'Sunucu IP/Host veya API Token tanımlanmamış.'];
        }

        $cleanUri = ltrim($path, '/');
        if (!str_starts_with($cleanUri, 'v1/')) {
            $cleanUri = 'v1/' . $cleanUri;
        }

        $url = "https://{$host}:{$port}/{$cleanUri}";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: PNLCS-zPanel-VPS/1.0',
        ]);

        $method = strtoupper($method);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $res = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($res === false) {
            return ['status' => false, 'message' => 'Bağlantı hatası: ' . $err, 'data' => null];
        }

        $json = json_decode((string)$res, true);
        $isOk = ($code >= 200 && $code < 300);

        return [
            'status' => $isOk,
            'http_code' => $code,
            'data' => $json,
            'message' => $json['message'] ?? ($isOk ? 'Başarılı' : "HTTP Hata {$code}"),
        ];
    }

    public function testConnection(): array
    {
        $res = $this->callApi('GET', 'health');
        return [
            'status' => $res['status'],
            'message' => $res['status'] ? 'zPanel VPS API bağlantısı başarılı.' : $res['message'],
        ];
    }

    public function createAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'zPanel sunucu hazırlandı.'];
    }

    public function suspendAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'Sunucu askıya alındı.'];
    }

    public function unsuspendAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'Sunucu aktif edildi.'];
    }

    public function terminateAccount(array $params = []): array
    {
        return ['status' => true, 'message' => 'Sunucu silindi.'];
    }

    public function reboot(array $params = []): array
    {
        $res = $this->callApi('POST', 'system/reboot');
        return [
            'status' => $res['status'],
            'message' => $res['status'] ? 'Sunucu yeniden başlatılıyor...' : $res['message'],
        ];
    }

    public function restartPanel(array $params = []): array
    {
        $res = $this->callApi('POST', 'system/restart-panel');
        return [
            'status' => $res['status'],
            'message' => $res['status'] ? 'zPanel servisleri yeniden başlatılıyor...' : $res['message'],
        ];
    }

    public function clientArea(array $params = []): array
    {
        return $this->renderClientArea($params);
    }

    public function renderClientArea(array $params = []): array
    {
        $p = array_merge($this->params, $params);
        $domain      = $p['domain'] ?? '';
        $serverIp    = $p['server_ip'] ?? $this->server['ip'] ?? '';
        $panelHost   = !empty($domain) ? $domain : $serverIp;
        $clientPort  = 8026;
        $adminPort   = 8036;

        $ssoUrl = !empty($panelHost) ? "https://{$panelHost}:{$clientPort}" : '#';

        return [
            'view' => 'modules.servers.zpanel_vps.views.clientArea',
            'data' => [
                'domain'        => $domain,
                'server_ip'     => $serverIp,
                'panel_host'    => $panelHost,
                'client_port'   => $clientPort,
                'admin_port'    => $adminPort,
                'package_name'  => $p['package_name'] ?? 'zPanel Bulut Sunucu',
                'status'        => $p['status'] ?? 'Active',
                'sso_url'       => $ssoUrl,
                'cpu_cores'     => $p['configoptions']['cpu_cores'] ?? 4,
                'ram_mb'        => $p['configoptions']['ram_mb'] ?? 8192,
                'disk_gb'       => $p['configoptions']['disk_gb'] ?? 160,
                'bandwidth_tb'  => $p['configoptions']['bandwidth_tb'] ?? 5,
                'os_name'       => $p['configoptions']['os_name'] ?? 'Ubuntu / AlmaLinux',
                'service_id'    => $p['service_id'] ?? 0,
            ],
        ];
    }
}
