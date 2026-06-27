# Contract: Penyedia Snapshot

## Kontrak Bisnis

- `penyedia` adalah master data yang boleh berubah
- data yang sudah dipakai di kegiatan harus tersalin ke snapshot
- snapshot menjadi sumber data final untuk dokumen dan laporan historis
- master penyedia boleh dihapus jika tidak lagi dibutuhkan oleh contract aktif

## Kontrak Teknis

- boundary input tetap melalui controller, request, use case, dan action
- data snapshot harus disimpan di layer domain, bukan di view
- pembacaan dokumen final tidak boleh bergantung pada master live bila snapshot sudah tersedia
- test menjadi pengunci kontrak perilaku

## Aturan Delete

- delete master tidak boleh merusak histori kegiatan yang sudah memakai snapshot
- jika data masih dipakai oleh flow aktif yang belum snapshot, delete harus diblokir
- pesan error delete harus menjelaskan alasan bisnis secara ringkas

## Kriteria Selesai

- snapshot tersimpan saat penyedia dipakai
- laporan tetap konsisten setelah master dihapus
- test delete, update, dan report lulus

