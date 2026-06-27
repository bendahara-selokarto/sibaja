# AI EXECUTION CONTRACT

Dokumen ini adalah sumber kebenaran kerja AI untuk repository `sibaja`.
Dokumen pengguna utama ada di `README.md`.
Dokumen operasional yang sudah ada ada di `docs/ops/`.

## 0. Prioritas

Jika ada konflik instruksi, gunakan urutan berikut:

1. `AGENTS.md`
2. `README.md`
3. Dokumen di `docs/ops/`
4. Konvensi Laravel yang sudah ada di kode

## 1. Ringkasan Stack

- Laravel 11
- Blade
- Vite
- JavaScript dan Vue pada sisi frontend bila diperlukan
- Testing dengan PHPUnit / Laravel test suite

## 2. Kontrak Kerja AI

- baca file yang relevan saja, jangan scan seluruh project tanpa alasan
- utamakan patch minimal dan jangan rewrite besar kalau tidak perlu
- jangan ubah file yang tidak terkait langsung dengan tugas
- kalau ada risiko behavior drift, jelaskan sebelum lanjut
- kalau perlu klarifikasi yang penting, ajukan pertanyaan singkat dan spesifik
- kalau user memberi referensi file, jadikan itu titik awal sebelum menebak file lain
- setiap patch wajib terdokumentasi dulu di tiga dokumen markdown: `plan`, `contract`, dan `todo`
- urutan kerja baku: `plan -> contract -> todo -> code patch -> validation -> final report`
- dokumen yang dipakai harus berada dalam format `.md`
- jika perubahan melibatkan beberapa file/fitur, update ketiga dokumen itu sebelum patch kode
- jika perubahan kecil benar-benar satu baris dan tidak mengubah perilaku, tetap buat catatan singkat di `todo`

## 3. Pola Arsitektur

Gunakan pemisahan layer yang sudah dipakai di repo ini:

- `app/Http/Controllers` untuk orchestration request dan response
- `app/Http/Requests` untuk validasi input
- `app/UseCases` untuk business flow
- `app/Actions` untuk operasi terfokus yang dipanggil use case atau controller
- `app/Repositories` dan `app/Contracts` untuk boundary data access
- `app/Models` untuk model domain
- `app/Policies` untuk izin akses
- `app/Console/Commands` untuk command operasional
- `app/Support` dan `app/Helpers` untuk utilitas yang memang lintas concern

Aturan utama:

- jangan taruh business logic berat di controller
- jangan duplikasi query kompleks di banyak tempat
- jangan pindahkan aturan domain ke view
- jika sudah ada use case, perluas use case tersebut sebelum membuat jalur baru
- jika ada alur data yang sudah ditangani `UseCase`, pertahankan boundary itu
- jika ada aksi CRUD kecil yang lebih pas di `Action`, jangan pindahkan logic ke controller

## 4. Fokus Domain

Repo ini banyak berisi fitur:

- `pemberitahuan`
- `penyedia`
- `penawaran`
- `negosiasi`
- `pembayaran`
- `kegiatan`
- `profile`
- `auth`
- `audit sync` antara `pemberitahuan` dan `pemberitahuan_penyedia`

Saat mengubah fitur domain, ikuti alur yang paling dekat dengan pola existing:

1. baca controller, request, use case, model, policy, dan test yang terdampak
2. lakukan patch minimal
3. update test yang relevan
4. verifikasi hasil dengan test terarah

Fitur yang sudah punya pola jelas di repo ini:

- `PenyediaController` + `PenyediaRequest` + `PenyediaPolicy` + `PenyediaRepository`
- `PemberitahuanController` + `PemberitahuanRequest` + `UpsertPemberitahuanUseCase`
- `PenawaranHargaController` + `PenawaranHargaRequest` + use case penawaran
- `PembayaranController` + `PembayaranRequest` + use case pembayaran
- `KegiatanController` + `KegiatanRequest` + use case kegiatan
- `NegosiasiHargaController` + `NegosiasiRequest` + use case negosiasi

## 5. Validasi

Prioritaskan validasi sesuai dampak:

- perubahan kecil: jalankan test terarah
- perubahan fitur: jalankan test feature terkait
- perubahan lintas area: jalankan suite yang lebih luas
- perubahan command artisan: jalankan test command yang relevan
- perubahan PDF: jalankan test feature/use case yang memverifikasi output dokumen

Perintah yang umum dipakai:

- `php artisan test`
- `php artisan test --filter=...`
- `npm run build`
- `php artisan audit:pemberitahuan-penyedia-sync`

Kalau perubahan menyentuh PDF, command artisan, atau query/data flow, pastikan ada test yang menutup perilaku baru.
Kalau perubahan menyentuh audit sync, cek command dan test `tests/Feature/PemberitahuanPenyediaAuditCommandTest.php`.

## 6. Konvensi Penamaan

- pakai nama yang konsisten dengan bahasa domain yang sudah ada di repo
- hindari istilah baru kalau istilah lama sudah jelas dan dipakai luas
- kalau ada istilah teknis yang muncul ke user, ubah ke bahasa yang lebih natural
- pertahankan istilah yang sudah menjadi kontrak file, class, atau route yang stabil
- untuk pesan UI, pilih bahasa Indonesia yang natural dan ringkas

## 7. Dokumentasi

Kalau perubahan menyentuh audit atau proses operasional:

- update dokumen di `docs/ops/`
- pastikan instruksi di README tetap sinkron
- kalau ada command baru yang penting, tulis contoh pemakaiannya
- kalau command sudah ada dan hanya perilakunya berubah, perbarui contoh atau catatan penggunaan

Standar dokumen kerja untuk semua perubahan:

- `docs/process/PLAN_*.md` untuk maksud, tujuan, ruang lingkup, dan risiko
- `docs/process/CONTRACT_*.md` untuk kontrak teknis/bisnis yang dikunci sebelum patch
- `docs/process/TODO_*.md` untuk daftar langkah kerja dan statusnya
- file patch kode hanya dibuat setelah tiga dokumen di atas ada dan sinkron

## 8. Domain Notes

- `pemberitahuan` dan `penyedia` punya hubungan sync yang diuji dan diaudit
- `app/Console/Commands/AuditPemberitahuanPenyediaSyncCommand.php` adalah command operasional yang harus dijaga konsistensinya dengan `app/Support/PemberitahuanPenyediaSyncAudit.php`
- contract aktif `penyedia` saat ini disederhanakan ke `kop_surat`; `logo_penyedia` dan `data_dukung` hanya artefak historis di migration/test lama dan jangan dijadikan jalur aktif baru
- PDF berada di `resources/views/pdf/` dan sering dipakai oleh use case report
- repository ini sudah memakai test feature dan unit untuk memastikan policy, helper, use case, dan command tetap stabil

## 9. Quality Gate

Sebelum selesai, pastikan:

- perubahan sesuai permintaan
- test relevan lulus
- tidak ada file tidak relevan yang ikut berubah
- behavior lama tidak drift tanpa alasan
- perubahan tidak memecah boundary controller, request, use case, policy, dan repository
