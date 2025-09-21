@extends('errors::frameless')
@section('title', __('Forbidden'))
@section('code', '403')
@section('message')
     <x-bladewind::error
        heading="Dilarang mengakses halaman ini"
        description="Maaf, Anda tidak memiliki izin untuk mengakses halaman ini."
        button_text="Kembali ke Beranda"
        button_url="/">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/403.svg') }}" alt="403 image">
        </x-slot>
    </x-bladewind::error>
@endsection
