# zPanel Dedicated & VPS Sunucu Modülü API Dokümantasyonu (PNLCS)

zPanel VPS sunucu modülü, PNLCS (Laravel 13) üzerinden sunucu üzerindeki zPanel Core REST API (:8056) ile haberleşir. Web sunucusu **zMaxServer (OpenLiteSpeed & LSPHP / LSAPI)** mimarisiyle yapılandırılmıştır.

---

## 1. Port ve Servis Haritası

- **8026 / TCP (HTTPS):** Müşteri Kontrol Paneli
- **8036 / TCP (HTTPS):** Root / Yönetici Paneli
- **8046 / TCP (HTTPS):** Webmail Servisi
- **8056 / TCP (HTTPS):** Core REST API

---

## 2. API Yetkilendirme

```http
Authorization: Bearer <server_api_key>
Content-Type: application/json
Accept: application/json
```

---

## 3. Desteklenen PNLCS Metotları ve API Uç Noktaları

| PNLCS Metodu | HTTP Yöntemi | zPanel Core API Uç Noktası | Açıklama |
|---|---|---|---|
| `testConnection()` | `GET` | `/v1/health` | API ve sistem bağlantı doğrulaması |
| `reboot()` | `POST` | `/v1/system/reboot` | Sunucuyu işletim sistemi seviyesinde yeniden başlatma |
| `restartPanel()` | `POST` | `/v1/system/restart-panel` | zPanel Core ve zMaxServer servislerini yeniden başlatma |
| `changePackage()` | Dahili | - | vCPU, RAM ve disk donanım özelliklerini güncelleme |
| `clientArea()` | Blade | `views/clientArea.blade.php` | Donanım metrikleri, port bilgileri ve yönetim eylemleri |
