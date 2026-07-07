# Plan: Catatan Release

## Maksud

Mencatat riwayat perubahan aplikasi `sibaja` secara terstruktur untuk memudahkan pemantauan dan kolaborasi pada pengembangan berikutnya.

## Tujuan

- Menyediakan satu berkas `CHANGELOG.md` di root repositori sebagai sumber informasi rilis resmi.
- Membantu developer dan AI agen berikutnya memahami apa saja yang berubah pada versi sebelumnya.
- Menerapkan format standar "Keep a Changelog" agar terdokumentasi dengan baik.

## Ruang Lingkup

- Pembuatan berkas `CHANGELOG.md` di root repositori.
- Pembuatan berkas `PLAN_catatan_release.md`, `CONTRACT_catatan_release.md`, dan `TODO_catatan_release.md` di direktori `docs/process/`.

## Di Luar Ruang Lingkup

- Pembuatan rilis otomatis menggunakan GitHub Action / CI-CD.
- Pengisian riwayat perubahan masa lalu secara lengkap (hanya berfokus pada struktur dasar dan penyiapan pencatatan perubahan berikutnya).

## Risiko

- Pengembang/AI lupa memperbarui `CHANGELOG.md` saat melakukan perubahan kode di masa depan. Mitigasi: mencantumkan kewajiban pembaruan di dokumen teknis/proses.

## Validasi

- Memastikan semua file markdown terformat dengan baik dan tautan internal valid.
- Menjalankan PHPUnit test suite (`php artisan test`) untuk memastikan tidak ada dampak buruk pada fungsionalitas sistem yang sudah ada.
