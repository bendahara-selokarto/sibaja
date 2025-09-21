@extends('errors::frameless')
@section('title', __('Tidak tersedia'))
@section('message')
     <x-bladewind::error
        heading="offline"
        description="Periksalah koneksi internet Anda dan coba lagi."
        button_url="/">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/offline.svg') }}" >
        </x-slot>
    </x-bladewind::error>
@endsection
