# 📦 Sistem Inventori KOPDES (Backend & Database)

Repositori ini berisi purwarupa (prototype) *Backend* dan *Database* untuk Sistem Inventori Koperasi Desa (KOPDES). Proyek ini dibangun menggunakan **Laravel 12**, **MySQL**, dan **Laravel Breeze** (untuk autentikasi dasar). 

Sesuai dengan studi kasus, fokus proyek ini adalah pada perancangan *schema database*, *business logic* (aturan bisnis), dan *testing* (pengujian) tanpa antarmuka pengguna (UI) terlebih dahulu.

---

## 👥 Tim Pengembang & Pembagian Tugas
Proyek ini dikerjakan secara kolaboratif menggunakan *branching* Git:
*   **Peba** (Backend Developer): Logika bisnis (`InventoryService`), Query Laporan (`LaporanService`), dan Relasi Model.
*   **Rizky** (Database Designer & Seeder): Perancangan Migrasi, Struktur Tabel, dan Data Dummy (`Seeder`).
*   **Sahlan** (Tester / QA): Penulisan *Feature Testing* (PHPUnit) untuk validasi aturan bisnis dan integritas database.
*   **Main Branch**: Kumpulan kode stabil hasil *merge* dari ketiga branch di atas.

---

## 🚀 Fitur Utama & Aturan Bisnis
1.  **Manajemen Stok:** Mengelola stok barang berdasarkan aktivitas `MASUK`, `KELUAR`, dan penyesuaian.
2.  **Validasi Stok (Anti-Minus):** Sistem secara otomatis menolak transaksi `KELUAR` jika jumlah yang diminta melebihi stok yang tersedia.
3.  **Multi-User Role:** Memiliki role `super_admin`, `admin` (mengelola master barang/kategori), dan `production`/petugas (mencatat transaksi).
4.  **Pencatatan Transaksi Detail:** Satu transaksi dapat memuat banyak barang melalui tabel `detail_transaksi`.
5.  **Laporan Otomatis:** Menyediakan *query* untuk melihat riwayat transaksi dan laporan barang yang stoknya menipis (di bawah `stok_minimum`).

---

