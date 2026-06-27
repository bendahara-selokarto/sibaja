# Docs Naming Convention

Dokumen ini menetapkan pola nama file markdown kerja untuk semua perubahan.

## Tujuan

- membuat dokumentasi patch konsisten di semua proyek
- memudahkan pencarian status kerja
- menjaga urutan kerja `plan -> contract -> todo -> code`

## Pola Nama

- `PLAN_<slug>.md`
- `CONTRACT_<slug>.md`
- `TODO_<slug>.md`

## Aturan Slug

- gunakan huruf kecil
- pisahkan kata dengan underscore
- hindari spasi
- hindari karakter khusus
- gunakan nama fitur atau inisiatif yang singkat dan jelas

## Contoh

- `PLAN_penyedia_snapshot.md`
- `CONTRACT_penyedia_snapshot.md`
- `TODO_penyedia_snapshot.md`

## Aturan Penggunaan

- satu inisiatif perubahan harus punya satu set tiga dokumen
- satu set dokumen harus konsisten slug-nya
- bila scope berubah, update dokumen yang sama, jangan bikin slug baru tanpa alasan
- dokumen patch kode baru boleh dibuat hanya setelah tiga dokumen itu ada

