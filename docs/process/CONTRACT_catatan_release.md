# Contract: Catatan Release

## Kontrak Bisnis

- Berkas `CHANGELOG.md` harus menjadi sumber kebenaran tunggal untuk mencatat riwayat versi/rilis aplikasi `sibaja`.
- Setiap kali ada fitur baru, perbaikan, atau perubahan alur bisnis yang dideploy atau diselesaikan dalam tugas (task), pengembang/AI wajib memperbarui `CHANGELOG.md`.

## Kontrak Teknis

- Penulisan di `CHANGELOG.md` menggunakan format standar Markdown dengan pengelompokan sub-kategori:
  - `Added` untuk fitur baru.
  - `Changed` untuk perubahan fungsionalitas yang ada.
  - `Deprecated` untuk fungsionalitas yang akan dihapus di masa mendatang.
  - `Removed` untuk fungsionalitas yang telah dihapus.
  - `Fixed` untuk perbaikan bug.
  - `Security` untuk peningkatan keamanan.
- Judul versi menggunakan tautan pembanding Git (jika nanti menggunakan tag git) atau sekadar teks versi berformat `[X.Y.Z] - YYYY-MM-DD`.
- Rilis baru diletakkan di bagian paling atas (di bawah `## [Unreleased]`).

## Kriteria Selesai

- Berkas `CHANGELOG.md` dibuat di root proyek.
- Berkas `CHANGELOG.md` memiliki bagian `## [Unreleased]` sebagai penampung perubahan berikutnya.
- Semua berkas proses (`PLAN_catatan_release.md`, `CONTRACT_catatan_release.md`, `TODO_catatan_release.md`) ada dan lengkap.
