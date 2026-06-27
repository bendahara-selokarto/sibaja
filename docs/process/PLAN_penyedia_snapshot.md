# Plan: Penyedia Snapshot

## Maksud

Membuat `penyedia` aman dipakai dalam alur bisnis kegiatan tanpa bergantung pada keberadaan master data yang bisa berubah atau dihapus.

## Tujuan

- kegiatan menyimpan salinan data penyedia saat penyedia dipilih
- histori dokumen tetap utuh walau master penyedia dihapus
- laporan dan PDF membaca snapshot, bukan master live, untuk data yang sudah dipakai

## Ruang Lingkup

- alur pemilihan penyedia pada pemberitahuan dan kegiatan
- penyimpanan snapshot data penyedia
- pembacaan snapshot pada laporan dan dokumen turunan
- aturan delete penyedia agar tidak merusak histori

## Di Luar Ruang Lingkup

- perubahan branding atau label UI non-bisnis
- perubahan audit `pemberitahuan` ke `pemberitahuan_penyedia`
- refactor domain lain di luar `penyedia`

## Risiko

- migrasi data snapshot bisa menambah kompleksitas
- laporan lama bisa berubah jika masih membaca master live
- delete master penyedia bisa perlu aturan baru untuk menjaga integritas histori

## Validasi

- test feature `penyedia`
- test relation `pemberitahuan` dan `penyedia`
- test report PDF yang memakai data penyedia
- test delete yang memastikan histori tetap aman

