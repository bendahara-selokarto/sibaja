@extends('errors::frameless')
@section('title', __('Unauthorized'))
@section('code', '401')
@section('message')
     <x-bladewind::error
        heading="Unauthorized"
        description="You do not have the necessary permissions to access this page."
        button_text="Kembali ke Beranda"
        button_url="/">
        <x-slot name="image">
            <img src="{{ asset('storage/errors/401.svg') }}" alt="401 image">
        </x-slot>
    </x-bladewind::error>
@endsection
