<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Snap Momen | Secure Print Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-yellow-100 text-gray-900 min-h-screen" x-data="{ open: false }">

        <div class="bg-white relative overflow-hidden">

        {{-- NAVBAR --}}
        <header class="relative z-20">
            <div class="max-w-6xl mx-auto px-5 py-5 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center font-extrabold text-sm text-gray-900 shadow">SM</div>
                    <span class="font-semibold text-xl tracking-tight">Snap Momen</span>
                </a>

                <nav class="hidden md:flex items-center gap-8 text-xs font-semibold uppercase tracking-widest text-gray-500">
                    <a href="#how" class="hover:text-gray-900">How it works</a>
                    <a href="#security" class="hover:text-gray-900">Security</a>
                    <a href="{{ route('orders.track') }}" class="hover:text-gray-900">Track Order</a>
                    <a href="#faq" class="hover:text-gray-900">FAQ</a>
                    <a href="#visit" class="hover:text-gray-900">Visit Us</a>
                </nav>

                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="border border-gray-200 text-xs font-bold uppercase tracking-widest text-yellow-700 px-6 py-3 hover:bg-yellow-50 transition">Login</a>
                    <a href="{{ route('home') }}" class="bg-yellow-400 text-gray-900 text-xs font-bold uppercase tracking-widest px-6 py-3 hover:bg-yellow-500 transition">Get Started &rarr;</a>
                </div>

                <button @click="open = !open" class="md:hidden p-2 rounded-lg bg-yellow-400 shadow" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div x-show="open" x-cloak class="md:hidden px-5 pb-4 flex flex-col gap-2 text-sm font-medium">
                <a href="#how" class="py-2">How it works</a>
                <a href="#security" class="py-2">Security</a>
                <a href="{{ route('orders.track') }}" class="py-2">Track Order</a>
                <a href="#visit" class="py-2">Visit Us</a>
                <a href="{{ route('login') }}" class="border border-gray-300 text-center py-2">Staff Login</a>
                <a href="{{ route('home') }}" class="bg-yellow-400 text-center py-2 font-semibold">Get Started</a>
            </div>
        </header>

        {{-- HERO --}}
        <section class="relative max-w-6xl mx-auto px-5 pt-10 pb-24 lg:pt-20 lg:pb-32 grid lg:grid-cols-2 gap-10 items-center">

            <div class="relative z-10">
                <h1 class="text-5xl lg:text-6xl font-extrabold leading-[1.1] tracking-tight text-gray-900">
                    Print securely.<br>Track every order.
                </h1>
                <p class="mt-6 text-lg text-gray-500 max-w-md leading-relaxed">
                    Snap Momen helps you send print files safely and follow your order status: easy, fast and secure. No account required.
                </p>
                <div class="mt-9 flex flex-wrap items-center gap-4">
                    <a href="{{ route('home') }}" class="bg-yellow-400 text-gray-900 text-sm font-bold uppercase tracking-widest px-8 py-4 shadow hover:bg-yellow-500 transition">Get Started &rarr;</a>
                    <a href="{{ route('orders.track') }}" class="text-sm font-bold uppercase tracking-widest text-gray-700 hover:text-yellow-600">Track My Order</a>
                </div>
            </div>

            {{-- Circle + phones --}}
            <div class="relative hidden md:block h-[520px]">
                <div class="absolute top-1/2 -translate-y-1/2 right-[-140px] w-[560px] h-[560px] rounded-full bg-yellow-400"></div>

                {{-- Phone 1: Submit order (belakang) --}}
                <div class="absolute left-4 top-16 w-48 h-[380px] rounded-[2rem] border-[8px] border-gray-900 bg-white shadow-2xl overflow-hidden -rotate-[14deg]">
                    <div class="bg-yellow-400 px-4 py-3 text-xs font-bold">New Order</div>
                    <div class="p-4 space-y-3">
                        <div class="h-2 w-16 bg-gray-300 rounded"></div>
                        <div class="h-7 border border-gray-200 rounded"></div>
                        <div class="h-2 w-20 bg-gray-300 rounded"></div>
                        <div class="h-7 border border-gray-200 rounded"></div>
                        <div class="border-2 border-dashed border-yellow-400 rounded-lg py-6 text-center text-[11px] text-gray-500">
                            Upload your file
                        </div>
                        <div class="bg-gray-900 text-yellow-300 text-center text-[11px] font-bold py-2 rounded">Submit</div>
                    </div>
                </div>

                {{-- Phone 2: Track order (depan) --}}
                <div class="absolute left-40 top-4 w-52 h-[400px] rounded-[2rem] border-[8px] border-gray-900 bg-white shadow-2xl overflow-hidden rotate-[16deg]">
                    <div class="bg-gray-900 text-yellow-300 px-4 py-3 text-xs font-bold">Track Order</div>
                    <div class="p-4 text-center">
                        <p class="text-[10px] uppercase tracking-widest text-gray-400">Order ID</p>
                        <p class="text-2xl font-extrabold tracking-widest text-gray-900 mt-1">7XQK29</p>
                    </div>
                    <div class="px-5 space-y-4 text-[11px]">
                        <div class="flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-yellow-400 flex items-center justify-center font-bold">&#10003;</span> Order received</div>
                        <div class="flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-yellow-400 flex items-center justify-center font-bold">&#10003;</span> Printing</div>
                        <div class="flex items-center gap-3 text-gray-400"><span class="w-5 h-5 rounded-full border-2 border-gray-300"></span> Ready for pickup</div>
                    </div>
                </div>
            </div>

            <p class="lg:absolute lg:bottom-6 lg:left-1/2 lg:-translate-x-1/2 text-center text-[11px] font-semibold uppercase tracking-widest text-gray-500">
                Snap Momen <span class="text-yellow-500">&hearts;</span>
            </p>
        </section>
    </div>

    {{-- Floating button (tukar nombor WhatsApp sebenar) --}}
    <a href="https://wa.me/601117510968" target="_blank" rel="noopener"
   class="fixed bottom-6 right-6 z-30 w-14 h-14 rounded-full bg-[#25D366] shadow-xl flex items-center justify-center hover:scale-105 transition" aria-label="WhatsApp Snap Momen">
    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="#ffffff" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    </a>

    {{-- HOW IT WORKS --}}
    <section id="how" class="bg-white py-14">
        <div class="max-w-6xl mx-auto px-5">
            <h2 class="text-2xl font-bold text-center mb-10">How it works</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6 rounded-2xl bg-yellow-50">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-yellow-400 flex items-center justify-center font-bold">1</div>
                    <h3 class="font-semibold mb-1">Upload your file</h3>
                    <p class="text-sm text-gray-600">Upload files directly through the system, not WhatsApp.</p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-yellow-50">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-yellow-400 flex items-center justify-center font-bold">2</div>
                    <h3 class="font-semibold mb-1">Get your Order ID</h3>
                    <p class="text-sm text-gray-600">Receive a 6-character code for your order reference.</p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-yellow-50">
                    <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-yellow-400 flex items-center justify-center font-bold">3</div>
                    <h3 class="font-semibold mb-1">Track the status</h3>
                    <p class="text-sm text-gray-600">Check the progress of your print job at any time.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECURITY --}}
    <section id="security" class="py-14">
        <div class="max-w-6xl mx-auto px-5">
            <h2 class="text-2xl font-bold text-center mb-10">Your files stay protected</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 text-center text-sm font-medium">
                <div class="bg-white rounded-xl shadow p-5">Encrypted customer data</div>
                <div class="bg-white rounded-xl shadow p-5">Secure, time-limited file access</div>
                <div class="bg-white rounded-xl shadow p-5">File type and size validation</div>
                <div class="bg-white rounded-xl shadow p-5">Auto-delete after retention period</div>
            </div>
        </div>
    </section>

    {{-- WHY SPMS --}}
    <section class="pb-14">
        <div class="max-w-6xl mx-auto px-5">
            <p class="text-center font-semibold text-gray-800 mb-8">Why does Snap Moment use Secure Print Management System?</p>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow p-6">
                    <h3 class="font-bold mb-3 text-red-600">The Old Way</h3>
                    <ul class="space-y-2 text-sm text-gray-700 list-disc pl-5">
                        <li>Files sent via WhatsApp</li>
                        <li>Orders mixed up and hard to manage</li>
                        <li>Customers don't know the printing status</li>
                    </ul>
                </div>
                <div class="bg-gray-900 text-white rounded-2xl shadow p-6">
                    <h3 class="font-bold mb-3 text-yellow-300">With SPMS</h3>
                    <ul class="space-y-2 text-sm list-disc pl-5">
                        <li>Upload files directly in a secure system</li>
                        <li>All orders managed in one dashboard</li>
                        <li>Check status with Order ID</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="bg-white py-14">
        <div class="max-w-3xl mx-auto px-5" x-data="{ active: null }">
            <h2 class="text-2xl font-bold text-center mb-8">Frequently Asked Questions</h2>
            @php
                $faqs = [
                    ['q' => 'Do I need to register an account?', 'a' => 'No, you don\'t need to. Customers can submit orders and track them without an account.'],
                    ['q' => 'How do I check the status of my order?', 'a' => 'Enter the 6-character Order ID you received after submission on the Track Order page.'],
                    ['q' => 'Is my file safe?', 'a' => 'Customer data is encrypted, files are accessed via temporary links, and files are deleted after their storage period expires.'],
                    ['q' => 'What file types are allowed?', 'a' => 'The system checks the file type and size before upload. Complete information is available on the Submit Order page.'],
                ];
            @endphp
            <div class="space-y-3">
                @foreach($faqs as $i => $faq)
                <div class="border border-yellow-300 rounded-xl overflow-hidden">
                    <button @click="active = active === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex justify-between items-center px-5 py-4 text-left font-medium bg-yellow-50 hover:bg-yellow-100">
                        {{ $faq['q'] }}
                        <span x-text="active === {{ $i }} ? '−' : '+'" class="text-xl"></span>
                    </button>
                    <div x-show="active === {{ $i }}" x-cloak class="px-5 py-4 text-sm text-gray-700">{{ $faq['a'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-14">
        <div class="max-w-4xl mx-auto px-5">
            <div class="bg-yellow-400 border-2 border-gray-900 rounded-3xl p-10 text-center shadow-lg">
                <h2 class="text-2xl lg:text-3xl font-bold">Ready to print?</h2>
                <p class="mt-2 text-gray-800">Submit order now and pick up when it's ready.</p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('home') }}" class="bg-gray-900 text-yellow-300 font-semibold px-7 py-3 rounded-lg hover:bg-gray-700 transition">Get Started</a>
                    <a href="{{ route('login') }}" class="bg-white font-semibold px-7 py-3 rounded-lg hover:bg-gray-50 transition">Staff Login</a>
                </div>
            </div>
        </div>
    </section>

        {{-- VISIT US --}}
    <section id="visit" class="bg-white py-14">
        <div class="max-w-6xl mx-auto px-5">
            <h2 class="text-2xl font-bold text-center mb-10">Visit Snap Momen</h2>
            <div class="grid md:grid-cols-3 gap-6">

                {{-- Address --}}
                <div class="bg-yellow-50 rounded-2xl p-6">
                    <div class="w-11 h-11 mb-4 rounded-full bg-yellow-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <h3 class="font-semibold mb-2">Address</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Snapmomen<br>
                        40, Jalan Medan 10, Taman Medan<br>
                        46000 Petaling Jaya, Selangor
                    </p>
                    <a href="https://www.google.com/maps/search/?api=1&query=Snapmomen+40+Jalan+Medan+10+Taman+Medan+46000+Petaling+Jaya"
                       target="_blank" rel="noopener"
                       class="inline-block mt-4 text-sm font-semibold underline hover:text-yellow-700">Open in Google Maps</a>
                </div>

                {{-- Operating hours --}}
                <div class="bg-yellow-50 rounded-2xl p-6">
                    <div class="w-11 h-11 mb-4 rounded-full bg-yellow-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-semibold mb-2">Operating Hours</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li><span class="font-medium text-gray-800">Open daily:</span> 10.00am – 9.30pm</li>
                        <li><span class="font-medium text-gray-800">Friday break:</span> 12.30pm – 2.30pm (Friday prayers)</li>
                        <li>Closed on public holidays</li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="bg-yellow-50 rounded-2xl p-6">
                    <div class="w-11 h-11 mb-4 rounded-full bg-yellow-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </div>
                    <h3 class="font-semibold mb-2">Contact Us</h3>
                    <p class="text-sm text-gray-600">
                        <a href="tel:+601117510968" class="font-medium text-gray-800 hover:underline">011-1751 0968</a>
                    </p>
                    <p class="text-sm text-gray-600 mt-1">Follow us @snapmomen</p>
                    <a href="https://wa.me/601117510968" target="_blank" rel="noopener"
                       class="inline-block mt-4 bg-gray-900 text-yellow-300 text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-gray-700 transition">Chat on WhatsApp</a>
                </div>

            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-300 py-10">
        <div class="max-w-6xl mx-auto px-5 grid md:grid-cols-3 gap-8 text-sm">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-yellow-400 rounded-lg flex items-center justify-center font-bold text-gray-900">SM</div>
                    <span class="font-semibold text-white">Snap Momen</span>
                </div>
                <p>Secure Print Management System</p>
                <p class="mt-2 text-xs text-gray-400">40, Jalan Medan 10, Taman Medan, 46000 Petaling Jaya, Selangor<br>Open daily 10.00am – 9.30pm</p>
            </div>
            <div>
                <p class="font-semibold text-white mb-3">Quick links</p>
                <ul class="space-y-2">
                    <li><a href="{{ route('orders.create') }}" class="hover:text-yellow-300">Submit Order</a></li>
                    <li><a href="{{ route('orders.track') }}" class="hover:text-yellow-300">Track Order</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-yellow-300">Staff Login</a></li>
                </ul>
            </div>
            <div>
                <p class="font-semibold text-white mb-3">Connect</p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-gray-700 hover:bg-yellow-400 hover:text-gray-900 flex items-center justify-center text-xs font-bold">IG</a>
                    <a href="#" class="w-9 h-9 rounded-full bg-gray-700 hover:bg-yellow-400 hover:text-gray-900 flex items-center justify-center text-xs font-bold">FB</a>
                    <a href="#" class="w-9 h-9 rounded-full bg-gray-700 hover:bg-yellow-400 hover:text-gray-900 flex items-center justify-center text-xs font-bold">WA</a>
                </div>
            </div>
        </div>
        <p class="text-center text-xs text-gray-500 mt-8">&copy; {{ date('Y') }} Snap Momen. All rights reserved.</p>
    </footer>
</body>
</html>