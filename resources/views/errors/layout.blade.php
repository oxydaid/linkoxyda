<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        
        {{-- Kita gunakan CDN Tailwind & Alpine untuk Error Page agar tetap jalan --}}
        {{-- meskipun file build/vite error/hilang --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            /* Simple Glitch Effect Animation */
            .glitch {
                position: relative;
                color: white;
                font-size: 8rem;
                font-weight: 900;
                line-height: 1;
            }
            .glitch::before, .glitch::after {
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
            .glitch::before {
                left: 2px;
                text-shadow: -1px 0 #ff00c1;
                clip: rect(44px, 450px, 56px, 0);
                animation: glitch-anim-1 5s infinite linear alternate-reverse;
            }
            .glitch::after {
                left: -2px;
                text-shadow: -1px 0 #00fff9;
                clip: rect(44px, 450px, 56px, 0);
                animation: glitch-anim-2 5s infinite linear alternate-reverse;
            }
            @keyframes glitch-anim-1 {
                0% { clip: rect(30px, 9999px, 10px, 0); }
                20% { clip: rect(80px, 9999px, 90px, 0); }
                40% { clip: rect(10px, 9999px, 50px, 0); }
                60% { clip: rect(40px, 9999px, 20px, 0); }
                80% { clip: rect(70px, 9999px, 60px, 0); }
                100% { clip: rect(20px, 9999px, 80px, 0); }
            }
            @keyframes glitch-anim-2 {
                0% { clip: rect(10px, 9999px, 80px, 0); }
                20% { clip: rect(60px, 9999px, 10px, 0); }
                40% { clip: rect(20px, 9999px, 50px, 0); }
                60% { clip: rect(90px, 9999px, 20px, 0); }
                80% { clip: rect(10px, 9999px, 60px, 0); }
                100% { clip: rect(50px, 9999px, 30px, 0); }
            }
        </style>
    </head>
    <body class="antialiased bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center overflow-hidden relative">
        
        {{-- Background Pattern (Optional) --}}
        <div class="absolute inset-0 z-0 opacity-20" 
             style="background-image: radial-gradient(#4b5563 1px, transparent 1px); background-size: 30px 30px;">
        </div>

        {{-- Main Content --}}
        <div class="z-10 text-center px-4" x-data="{ hover: false }">
            
            {{-- Error Code with Glitch --}}
            <h1 class="glitch mb-4 select-none" data-text="@yield('code')">
                @yield('code')
            </h1>

            {{-- Message --}}
            <h2 class="text-2xl md:text-3xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-purple-500">
                @yield('message')
            </h2>
            
            <p class="text-gray-400 mb-8 max-w-md mx-auto">
                @yield('description')
            </p>

            {{-- Button Back --}}
            <a href="{{ url('/') }}" 
               @mouseover="hover = true" @mouseleave="hover = false"
               class="relative inline-flex items-center justify-center px-8 py-3 overflow-hidden font-bold text-white transition-all duration-300 bg-indigo-600 rounded-lg group focus:outline-none focus:ring">
                
                <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                <span class="relative flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-300" :class="hover ? '-translate-x-1' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Home
                </span>
            </a>
        </div>

        {{-- Footer --}}
        <div class="absolute bottom-5 text-gray-600 text-xs font-mono">
            E:@yield('code') | Oxyda Link
        </div>

    </body>
</html>