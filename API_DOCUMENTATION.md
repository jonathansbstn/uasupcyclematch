# 📖 Dokumentasi API UpcycleMatch v1

**Base URL:** `http://localhost:8000/api/v1`  
**Format Response:** `application/json`  
**Versi:** 1.0.0

---

## 🔐 Metode Autentikasi

Platform ini mendukung **tiga metode autentikasi** sesuai ketentuan tugas:

### 1. JWT Bearer Token
Metode utama. Token didapat dari endpoint `/auth/login`, kemudian disertakan di setiap request yang membutuhkan auth.

```
Authorization: Bearer <jwt_token>
```

**Karakteristik JWT yang digunakan:**
- Algoritma: `HS256` (HMAC SHA-256)
- TTL (masa aktif): 60 menit
- Refresh TTL: 2 minggu
- Blacklist: Aktif (token yang logout tidak bisa digunakan lagi)
- Custom Claims: `role`, `name`, `email` ikut disertakan dalam payload

### 2. Basic Auth
Alternatif untuk endpoint login. Email dan password dikirim dalam format Base64.

```
Authorization: Basic <base64(email:password)>
```

**Contoh:**
```
email: budi@example.com
password: password123
→ base64 → YnVkaUBleGFtcGxlLmNvbTpwYXNzd29yZDEyMw==

Authorization: Basic YnVkaUBleGFtcGxlLmNvbTpwYXNzd29yZDEyMw==
```

### 3. API Key
Untuk integrasi sistem eksternal (bukan user login). Disertakan sebagai header.

```
X-API-KEY: upcyclematch-dev-key-2026
```

> **API Keys tersedia:**
> - Development: `upcyclematch-dev-key-2026`
> - Production: `upcyclematch-prod-key-2026`

---

## 📋 Daftar Endpoint

### 🔑 Autentikasi

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `POST` | `/api/v1/auth/register` | — | Daftar akun baru, dapat JWT token langsung |
| `POST` | `/api/v1/auth/login` | — / Basic Auth | Login, dapatkan JWT token |
| `GET`  | `/api/v1/auth/me` | JWT | Profil user yang sedang login |
| `POST` | `/api/v1/auth/logout` | JWT | Logout, invalidasi token |

---

#### `POST /api/v1/auth/register`

**Request Body:**
```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "contributor"
}
```

> **role:** `contributor` atau `upcycler` (default: contributor)

**Response 201:**
```json
{
  "message": "User successfully registered",
  "user": {
    "id": 5,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "role": "contributor"
  },
  "token": "eyJ0eXAiOiJKV1Qi...",
  "token_type": "bearer"
}
```

---

#### `POST /api/v1/auth/login`

Mendukung **JSON body** dan **Basic Auth** secara bersamaan.

**Cara 1 — JSON Body:**
```json
{
  "email": "budi@example.com",
  "password": "password123"
}
```

**Cara 2 — Basic Auth Header:**
```
Authorization: Basic YnVkaUBleGFtcGxlLmNvbTpwYXNzd29yZDEyMw==
```

**Response 200:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1Qi...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": 5,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "role": "contributor",
    "koin": 0,
    "is_verified": false
  }
}
```

**Response 401 (salah password):**
```json
{
  "error": "Unauthorized, wrong email or password"
}
```

---

#### `GET /api/v1/auth/me`

**Headers:**
```
Authorization: Bearer eyJ0eXAiOiJKV1Qi...
```

**Response 200:**
```json
{
  "id": 5,
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "role": "contributor",
  "koin": 150,
  "saldo": 0,
  "is_verified": false
}
```

---

### 🔗 Endpoint API Key (Publik Eksternal)

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `GET`  | `/api/v1/public/waste-map` | API Key | Data peta limbah (GeoJSON) |
| `GET`  | `/api/v1/public/stats` | API Key | Statistik platform |

**Contoh Request:**
```http
GET /api/v1/public/waste-map HTTP/1.1
Host: localhost:8000
X-API-KEY: upcyclematch-dev-key-2026
Accept: application/json
```

**Response 401 (API Key salah/kosong):**
```json
{
  "success": false,
  "message": "Unauthorized: Invalid or missing API Key.",
  "hint": "Sertakan header X-API-KEY yang valid."
}
```

---

### 🧵 Limbah Kain (Textiles)

| Method | Endpoint | Auth | Role | Deskripsi |
|--------|----------|------|------|-----------|
| `GET`    | `/api/v1/textiles` | — | Semua | Daftar limbah (publik, dengan filter) |
| `GET`    | `/api/v1/textiles/{id}` | — | Semua | Detail limbah |
| `POST`   | `/api/v1/textiles` | JWT | Contributor | Tambah limbah baru |
| `PUT`    | `/api/v1/textiles/{id}` | JWT | Contributor | Update limbah milik sendiri |
| `DELETE` | `/api/v1/textiles/{id}` | JWT | Contributor | Hapus limbah (hanya jika `available`) |
| `POST`   | `/api/v1/textiles/{id}/claim` | JWT | Upcycler | Klaim limbah |

#### Query Parameters untuk `GET /api/v1/textiles`:

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `status` | string | Filter: `available`, `claimed`, `processing`, `completed` |
| `fabric_type` | string | Filter jenis kain (partial match) |
| `per_page` | integer | Jumlah per halaman (default: 15) |

**Response `GET /api/v1/textiles` (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Kain Batik Bekas",
      "fabric_type": "Batik Katun",
      "weight": "5.50",
      "address": "Jl. Pahlawan No. 10, Bandung",
      "status": "available",
      "latitude": "-6.917464",
      "longitude": "107.619123",
      "owner": {
        "id": 3,
        "name": "Rani Contributor",
        "whatsapp": "08123456789"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "total": 42
  }
}
```

---

### 📦 Pesanan (Orders)

| Method | Endpoint | Auth | Role | Deskripsi |
|--------|----------|------|------|-----------|
| `GET`   | `/api/v1/orders` | JWT | Semua | Pesanan sesuai role |
| `POST`  | `/api/v1/orders` | JWT | Contributor | Buat pesanan baru |
| `GET`   | `/api/v1/orders/{id}` | JWT | Pihak terkait | Detail pesanan |
| `PATCH` | `/api/v1/orders/{id}/status` | JWT | Upcycler | Update status pesanan |

**Status pesanan yang valid untuk PATCH:**

| Status | Deskripsi |
|--------|-----------|
| `processing` | Sedang dikemas |
| `shipped` | Sudah dikirim |
| `done` | Selesai/diterima |
| `cancelled` | Dibatalkan |

---

### ⚙️ Produksi (Upcycler)

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `POST` | `/api/v1/production/start` | JWT | Mulai produksi (body: `textile_id`) |
| `POST` | `/api/v1/production/finish` | JWT | Selesaikan produksi |
| `POST` | `/api/v1/production/upload` | JWT | Upload produk hasil (multipart/form-data) |

---

### 📊 Analytics (Publik)

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `GET` | `/api/v1/analytics` | — | Statistik platform UpcycleMatch |

**Response:**
```json
{
  "success": true,
  "summary": {
    "total_weight": 245.5,
    "total_products": 38,
    "total_upcyclers": 12,
    "material_savings": 12275000
  },
  "charts": {
    "monthly_waste": [...],
    "monthly_products": [...],
    "status_dist": { "available": 20, "claimed": 8, "processing": 5, "completed": 15 }
  }
}
```

---

## 🗄️ Migrasi & Relasi Database

### Tabel Utama dan Relasi

```
users (id, name, email, role, koin, saldo, is_verified)
  │
  ├──[has many]──► textiles (limbah kain)
  │                   │ user_id → users.id (pemilik/contributor)
  │                   │ claimed_by → users.id (upcycler)
  │                   └──[has one]──► products
  │
  ├──[has many]──► orders (sebagai buyer)
  │
  ├──[has many]──► koin_transactions
  ├──[has many]──► koin_withdrawals
  └──[has one]───► upcycler_profiles
```

### File Migrasi Utama:
- `create_users_table.php` — Tabel pengguna dengan multi-role
- `create_textiles_table.php` — Limbah kain dengan koordinat GPS
- `create_products_table.php` — Produk hasil upcycling
- `create_orders_table.php` — Pesanan dengan enum status
- `create_koin_transactions_table.php` — Riwayat transaksi koin
- `create_koin_withdrawals_table.php` — Pencairan saldo

---

## 🧪 Panduan Testing di Postman

### Langkah 1: Import Collection
1. Buka Postman
2. Klik **Import** → pilih file `UpcycleMatch_API.postman_collection.json`
3. Collection akan muncul di sidebar

### Langkah 2: Set Environment Variable
Variabel `base_url` sudah otomatis di-set ke `http://localhost:8000/api/v1`.  
Pastikan server Laravel berjalan:
```bash
php artisan serve
```

### Langkah 3: Login & Dapatkan Token
1. Buka folder **🔑 Autentikasi** → **Login - Dapat JWT Token**
2. Klik **Send**
3. Token JWT akan **otomatis tersimpan** ke variabel `jwt_token` (via Test script)

### Langkah 4: Test Endpoint Lainnya
Token sudah tersimpan otomatis — semua request yang butuh auth akan pakai `{{jwt_token}}`.

### Langkah 5: Test API Key
1. Buka folder **🔗 API Key**
2. Header `X-API-KEY` sudah terisi dengan `{{api_key}}`
3. Klik **Send** langsung

---

## ⚠️ Error Response Standar

| HTTP Code | Kondisi |
|-----------|---------|
| `200` | Sukses |
| `201` | Resource berhasil dibuat |
| `400` | Validasi gagal / request tidak valid |
| `401` | Token tidak valid / expired / API Key salah |
| `403` | Forbidden — role tidak memiliki izin |
| `404` | Resource tidak ditemukan |
| `422` | Unprocessable — data tidak memenuhi syarat bisnis |
| `500` | Internal Server Error |

**Format error standar:**
```json
{
  "success": false,
  "message": "Deskripsi error di sini"
}
```

---

*Dokumentasi ini dibuat untuk keperluan tugas Pemrograman Web — UpcycleMatch API v1*
