<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
            {{ __('Terima kasih sudah mendaftar! Sebelum mulai, bisa tolong verifikasi email kamu dengan klik tautan yang baru saja kami kirim? Kalau belum terima, kami akan kirim ulang, tenang saja.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Kami sudah mengirim tautan verifikasi baru ke email yang kamu gunakan saat daftar.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Kirim Lagi Email Verifikasi') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
