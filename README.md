# Laundry App (Laravel)

Aplikasi manajemen laundry berbasis framework Laravel dengan standar penulisan kode modern: analisis statis **PHPStan (Level 8)**, pengujian otomatis **PHPUnit**, linter ketat **Laravel Pint** yang mewajibkan `declare(strict_types=1);`, otomatisasi **CI/CD GitHub Actions**, serta kontainerisasi **Docker**.

---

## Fitur Utama

- **API Pembuatan Order**: `POST /api/orders`
  - Validasi ketat via Form Request (`StoreLaundryOrderRequest`).
  - Aturan bisnis kalkulasi otomatis via `OrderCalculationService`:
    - Tarif dasar: Rp 10.000 / kg.
    - Tarif express: Rp 20.000 / kg (2x tarif dasar).
    - Batas minimum berat: 2 kg.
  - Status awal pesanan: `Pending` (`Menunggu Antrean`).
- **Quality Gates & Guardrails**:
  - `declare(strict_types=1);` diwajibkan di setiap berkas PHP.
  - PHPStan Level 8 (Larastan).
  - 100% lulus pengujian otomatis (Unit Test & Feature Test).
- **CI/CD Pipeline**: GitHub Actions workflow (`.github/workflows/ci.yml`).
- **Docker Production Ready**: `Dockerfile`, `docker-compose.yml`, dan `nginx.conf`.

---

## Persyaratan Sistem

- PHP >= 8.2 (Direkomendasikan PHP 8.3)
- Composer >= 2.x
- SQLite3 / PDO SQLite
- Docker & Docker Compose (opsional untuk container)

---

## Panduan Instalasi Lokal

```bash
# 1. Clone repositori
git clone https://github.com/yudigf/laundry-app.git
cd laundry-app

# 2. Instal dependensi
composer install

# 3. Setup environment & database
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force

# 4. Jalankan server pengembangan
php artisan serve
```

---

## Pemeriksaan Kualitas Kode (Quality Gate)

Jalankan perintah berikut untuk mengeksekusi linter, analisis statis, dan testing sekaligus:

```bash
composer check
```

Atau jalankan secara terpisah:
```bash
composer lint       # Cek strict typing & gaya kode via Pint
composer lint:fix   # Format otomatis kode
composer phpstan    # Analisis statis PHPStan level 8
composer test       # Menjalankan PHPUnit test suite
```

---

## Menjalankan dengan Docker

```bash
# Build dan jalankan service app (PHP-FPM) dan web (Nginx port 8000)
docker compose up -d --build

# Cek status kontainer
docker compose ps
```

---

## Dokumentasi Endpoint API

### `POST /api/orders`

**Headers**:
```http
Content-Type: application/json
Accept: application/json
```

**Payload Request**:
```json
{
    "customer_name": "Ahmad Dahlan",
    "weight_kg": 3.5,
    "service_type": "standar"
}
```

*Nilai `service_type` yang diterima: `standar` atau `express`.*

**Response Sukses (HTTP 201 Created)**:
```json
{
    "message": "Pesanan berhasil dibuat.",
    "data": {
        "id": 1,
        "order_number": "LND-20260915-0001",
        "customer_name": "Ahmad Dahlan",
        "service_type": "standar",
        "weight_kg": 3.5,
        "unit_price": 10000,
        "total_amount": 35000,
        "status": "Menunggu Antrean"
    }
}
```
