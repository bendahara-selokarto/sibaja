# Review App Refactor Prep

Dokumen ini adalah hasil pemeriksaan menyeluruh aplikasi saat status produksi.
Tujuannya adalah menjadi baseline sebelum refaktor berikutnya.

## Ringkasan

- Aplikasi masih dominan Laravel Blade dengan domain procurement yang cukup besar.
- Layerisasi sudah mulai terbentuk: controller, request, use case, action, repository, policy, dan support.
- Alur bisnis `penyedia` sudah paling kompleks dan paling berisiko untuk refaktor karena dipakai lintas `pemberitahuan`, `penawaran`, `negosiasi`, `pembayaran`, dan `kegiatan`.
- Ada jejak historis schema dan view lama yang masih bercampur dengan contract aktif.

## Observasi Arsitektur

- `app/Http/Controllers` masih berisi orchestration utama, dan sebagian besar controller sudah cukup tipis.
- `app/UseCases` dipakai untuk beberapa flow report dan write flow, tetapi belum seragam di semua domain.
- `app/Actions/Penyedia` sudah memisahkan operasi CRUD penyedia dari controller.
- `app/Repositories/PenyediaRepository.php` menjadi boundary data access yang cukup jelas.
- `app/Policies/PenyediaPolicy.php` menangani otorisasi dasar, namun belum cukup untuk mengunci seluruh risiko bisnis.

## Temuan Bisnis

### 1. Master penyedia masih bisa dihapus oleh creator

- Saat ini `delete` pada penyedia hanya diblokir jika penyedia sudah direferensikan di penawaran atau pivot pemberitahuan.
- Jika belum masuk referensi tersebut, creator masih bisa menghapus master penyedia.
- Ini aman untuk integritas dokumen yang sudah jadi, tetapi berisiko bila penyedia sudah dipakai sebagai daftar kerja oleh user lain.

### 2. Snapshot bisnis belum tersedia

- Aplikasi masih banyak bergantung pada master live `penyedia`.
- Untuk kebutuhan produksi dan histori, ini berisiko karena perubahan atau penghapusan master bisa memengaruhi dokumen lama.
- Kebutuhan bisnis yang lebih aman adalah snapshot data penyedia saat dipakai oleh kegiatan atau dokumen turunan.

### 3. Jejak field historis masih ada

- `logo_penyedia` dan `data_dukung` sudah tidak dipakai pada contract aktif penyedia.
- Namun jejaknya masih muncul di migration lama dan test historis.
- Ini tidak mengganggu produksi, tetapi berisiko menimbulkan interpretasi yang salah saat refaktor.

## Temuan Teknis

### 1. Kontrak penyedia perlu lebih eksplisit

- Contract aktif saat ini sudah menyederhana ke `kop_surat`.
- Dokumen kerja sudah perlu mengunci bahwa `logo_penyedia` dan `data_dukung` hanya historis.

### 2. Banyak keputusan bisnis masih tertanam di model

- `Penyedia::isReferencedInProcurement()` masih memeriksa referensi penawaran dan pivot.
- `Penyedia::cukupUntukKegiatan()` masih mengatur syarat minimal penyedia.
- Fungsi seperti ini masih layak, tetapi perlu dipetakan apakah itu domain rule atau sekadar utilitas model.

### 3. Kegiatan dan pemberitahuan belum memisahkan snapshot dari master

- `KegiatanController` dan `PemberitahuanController` masih menggunakan master penyedia untuk beberapa view/report.
- `Pemberitahuan` memang sudah punya pivot `pemberitahuan_penyedia`, tetapi belum menjadi snapshot immutable yang kuat.

## Risiko Refaktor

- Refaktor langsung ke kode produksi berisiko memutus alur PDF/report yang sekarang masih membaca master live.
- Menghapus field atau relasi tanpa snapshot akan merusak histori dokumen lama.
- Perubahan delete policy tanpa pemetaan dependency bisa membuat data produksi hilang atau justru terlalu terkunci.

## Rekomendasi Refaktor

1. Buat snapshot penyedia untuk dokumen yang sudah dipakai.
2. Tentukan master/live data mana yang masih boleh dibaca langsung.
3. Pisahkan contract aktif dari jejak historis di migration dan test.
4. Buat aturan delete yang berbasis histori dan snapshot, bukan sekadar ownership.
5. Jadikan dokumen plan, contract, dan todo sebagai prasyarat perubahan.

## Area Prioritas Untuk Refaktor Berikutnya

- `PenyediaController`
- `PenyediaPolicy`
- `DeletePenyediaAction`
- `PenyediaMediaService`
- `KegiatanController`
- `PemberitahuanController`
- PDF penawaran dan pembayaran
- migration / schema snapshot

## Status Saat Ini

- Produksi: tetap berjalan
- Refaktor: belum dimulai
- Dokumen: perlu dijadikan baseline sebelum patch berikutnya

