# Audit Sync `pemberitahuan` dan `penyedia`

Dokumen ini untuk menjalankan audit read-only antara kolom legacy `pemberitahuans.penyedia` dan pivot `pemberitahuan_penyedia`.

## Tujuan

Audit ini memastikan data transisi masih sinkron sebelum compat layer legacy dipensiunkan.

## Aman atau tidak

- Command ini read-only.
- Tidak mengubah isi tabel.
- Aman dijalankan di staging atau produksi.

## Prasyarat

- Aplikasi sudah ter-deploy dengan command audit terbaru.
- Migrasi `pemberitahuan_penyedia` sudah dijalankan.
- PHP CLI tersedia, atau gunakan wrapper PowerShell repo.

## Perintah cepat

Untuk membuat laporan JSON bertimestamp:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\audit-pemberitahuan-penyedia-sync.ps1 -SaveReport
```

Untuk melihat output langsung tanpa menyimpan file:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\audit-pemberitahuan-penyedia-sync.ps1 -Json
```

Untuk membatasi sampel mismatch:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\audit-pemberitahuan-penyedia-sync.ps1 -Limit 25 -SaveReport
```

## Lokasi laporan

Default report tersimpan di:

```text
storage/app/audits/pemberitahuan-penyedia-sync-YYYYMMDD-HHMMSS.json
```

## Cara membaca hasil

Field penting:

- `matching_records`: jumlah `pemberitahuan` yang legacy dan pivot-nya sama.
- `mismatched_records`: jumlah `pemberitahuan` yang ada selisih.
- `legacy_only_links`: jumlah relasi yang hanya ada di kolom legacy.
- `pivot_only_links`: jumlah relasi yang hanya ada di pivot.

Interpretasi cepat:

- `mismatched_records = 0`, `legacy_only_links = 0`, `pivot_only_links = 0`
  Data sinkron. Aman lanjut ke audit tahap berikutnya.
- `legacy_only_links > 0`
  Ada data lama yang belum lengkap ter-backfill ke pivot.
- `pivot_only_links > 0`
  Ada pivot yang tidak cocok dengan kolom legacy. Perlu cek alur input/update historis.

## Tindak lanjut aman

Jika mismatch ditemukan:

1. Simpan file JSON hasil audit.
2. Catat waktu audit dan commit aplikasi yang aktif.
3. Jangan hapus kolom legacy.
4. Review `pemberitahuan_id` pada daftar mismatch.
5. Validasi satu per satu terhadap data bisnis sebelum melakukan sinkronisasi manual.

## Kapan aman mempertimbangkan pensiun legacy

Minimal setelah:

1. Audit berulang menunjukkan `mismatched_records = 0`.
2. Tidak ada `legacy_only_links`.
3. Tidak ada `pivot_only_links`.
4. Semua pembacaan aplikasi sudah lewat helper/relasi baru.
5. Ada backup sebelum perubahan skema final.
