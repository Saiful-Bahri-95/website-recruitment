@props([
    'activeStep' => null,
    'showStepIndicator' => true,
])

@php
    $user = auth()->user();
    $progress = $user->getProgressData();
    $appStatus = $progress['status'];

    $steps = [
        ['id' => 1, 'label' => 'Biodata',    'icon' => 'user',        'route' => route('biodata.edit'),    'done' => $progress['steps']['biodata']],
        ['id' => 2, 'label' => 'Dokumen',    'icon' => 'file',        'route' => route('documents.index'), 'done' => $progress['steps']['dokumen']],
        ['id' => 3, 'label' => 'Pembayaran', 'icon' => 'credit-card', 'route' => route('payment.index'),   'done' => $progress['steps']['pembayaran']],
        ['id' => 4, 'label' => 'Verifikasi', 'icon' => 'shield',      'route' => route('status'),          'done' => $progress['steps']['verifikasi']],
    ];

    // Tentukan state tiap step: done / active / upcoming / rejected
    foreach ($steps as $i => &$step) {
        if ($appStatus === 'rejected' && $step['id'] === 4) {
            // Special case: rejected → step 4 jadi alert merah
            $step['state'] = 'rejected';
        } elseif ($step['done']) {
            $step['state'] = 'done';
        } elseif ($activeStep === $step['id']) {
            // Halaman saat ini mendeklarasikan dirinya sebagai step tertentu
            $step['state'] = 'active';
        } else {
            // Step pertama yang belum done dianggap "next to do" — opsional
            $step['state'] = 'upcoming';
        }
    }
    unset($step);

    $initials = collect(explode(' ', $user->name))
        ->take(2)
        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
        ->implode('');
@endphp

<div class="min-h-screen">

    {{-- ============ TOP NAVBAR - GLASSMORPHISM ============ --}}
    <div class="sticky top-0 z-40 glass-card border-b border-cream-300/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 py-4 flex items-center justify-between">

            {{-- Left: Brand --}}
            <a href="{{ route('dashboard') }}" class="hover:opacity-80 transition-opacity">
                <x-brand.wordmark />
            </a>

            {{-- Right: User menu --}}
            <div class="flex items-center gap-3">

                {{-- Notification --}}
                <button type="button" class="relative p-2.5 hover:bg-paper rounded-xl transition-all group">
                    <svg class="w-5 h-5 text-navy" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 shadow-gold-glow animate-pulse"></span>
                </button>

                {{-- User info + avatar --}}
                <div class="hidden md:flex items-center gap-3 pl-3 ml-1 border-l border-cream-300/50">
                    <div class="text-right">
                        <div class="text-sm font-semibold text-navy leading-tight">{{ $user->name }}</div>
                        <div class="text-xs text-cream-500 leading-tight">{{ $user->email }}</div>
                    </div>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl bg-gradient-navy flex items-center justify-center font-display text-sm font-bold text-gold shadow-navy-glow">
                            {{ $initials }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-green-500 border-2 border-white"></span>
                    </div>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-2.5 hover:bg-paper rounded-xl transition-all" title="Keluar">
                        <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ STEP INDICATOR - MODERN ============ --}}
    @if($showStepIndicator)
        <div class="bg-white/60 backdrop-blur-sm border-b border-cream-300/50">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 py-7">
                <div class="flex items-center justify-between gap-2 overflow-x-auto" style="padding-top: 1.25rem; padding-bottom: 1.25rem; padding-left: 1.25rem; padding-right: 1.25rem;">

                    @foreach($steps as $index => $step)
                        <div class="flex items-center flex-1 min-w-fit">

                            {{-- Step Circle dengan glow effect --}}
                            <a href="{{ $step['route'] }}" class="flex flex-col items-center gap-2 min-w-fit relative group hover:opacity-90 transition-opacity">

                                {{-- Glow ring untuk active state --}}
                                @if($step['state'] === 'active')
                                    <div class="absolute -inset-2 bg-navy/20 rounded-full blur-md animate-pulse"></div>
                                @endif

                                <div @class([
                                    'relative w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-105',
                                    'bg-gradient-navy text-gold shadow-navy-glow scale-110' => $step['state'] === 'active',
                                    'bg-gradient-to-br from-gold-400 to-gold-600 text-navy shadow-gold-glow' => $step['state'] === 'done',
                                    'bg-gradient-to-br from-red-500 to-red-600 text-white shadow-brand-md' => $step['state'] === 'rejected',
                                    'bg-white border-2 border-cream-300 text-cream-500' => $step['state'] === 'upcoming',
                                ])>
                                    @if($step['state'] === 'done')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @elseif($step['state'] === 'rejected')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        @switch($step['icon'])
                                            @case('user')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                                </svg>
                                                @break
                                            @case('file')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                </svg>
                                                @break
                                            @case('credit-card')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                                </svg>
                                                @break
                                            @case('shield')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                                                </svg>
                                                @break
                                        @endswitch
                                    @endif
                                </div>

                                {{-- Step Label --}}
                                <div class="text-center mt-1">
                                    <div class="text-[9px] tracking-wider-2 uppercase text-cream-500 font-medium">Step {{ $step['id'] }}</div>
                                    <div @class([
                                        'text-xs font-semibold tracking-wide',
                                        'text-navy' => in_array($step['state'], ['active', 'done']),
                                        'text-red-600' => $step['state'] === 'rejected',
                                        'text-cream-500' => $step['state'] === 'upcoming',
                                    ])>
                                        {{ $step['label'] }}
                                    </div>
                                </div>
                            </a>

                            {{-- Connector Line dengan gradient --}}
                            @if($index < count($steps) - 1)
                                <div @class([
                                    'flex-1 h-0.5 mx-3 -mt-7 rounded-full',
                                    'bg-gradient-to-r from-gold-400 to-gold-600' => $step['state'] === 'done',
                                    'bg-cream-300' => $step['state'] !== 'done',
                                ])></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ============ MAIN CONTENT ============ --}}
    <main class="max-w-7xl mx-auto px-6 lg:px-10 py-10">
        {{ $slot }}
    </main>

</div>
