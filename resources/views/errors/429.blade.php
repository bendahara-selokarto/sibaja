@extends('errors::frameless')
@section('title', __('too many requests'))
@section('code', '429')
@section('message')
     <x-bladewind::error
        heading="sibaja sedang sibuk"
        description="Terlalu banyak permintaan dari Anda dalam waktu singkat. Silakan coba lagi nanti."
        button_text="Kembali ke Beranda"
        button_url="/">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/429.svg') }}" >
        </x-slot>
    </x-bladewind::error>
@endsection
