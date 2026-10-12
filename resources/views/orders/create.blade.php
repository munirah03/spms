<x-guest-page>
<div class="max-w-2xl mx-auto space-y-6">

    <div class="text-center mb-2">
        <h2 class="text-2xl font-bold text-gray-800">Submit Printing Order</h2>
        <p class="text-gray-500 text-sm mt-1">No account needed — just fill in your details below.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="payment_method" value="counter">

        <!-- CARD 1: Customer Details -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-yellow-100 text-yellow-700 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                Customer Details
            </h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="customer_name" placeholder="e.g. Ahmad Bin Ali"
                        value="{{ old('customer_name', $prefillName ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number (WhatsApp) / Email</label>
                    <input type="text" name="contact_info" placeholder="e.g. 012-3456789"
                        value="{{ old('contact_info', $prefillContact ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Document</label>
                    <p class="text-xs text-gray-400 mb-2">Accepted formats: PDF, JPG, JPEG, PNG, DOCX max 5MB</p>
                    <input type="file" name="file" id="file-input"
                        accept=".pdf,.jpg,.jpeg,.png,.docx,application/pdf,image/jpeg,image/png,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-yellow-50 file:text-yellow-800 file:font-medium hover:file:bg-yellow-100 transition"
                        required>
                    <p id="file-error" class="hidden text-xs text-red-500 mt-1"></p>
                </div>
            </div>
        </div>

        <!-- CARD 2: Print Details -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-yellow-100 text-yellow-700 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                Print Details
            </h3>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-5 text-sm">
    <p class="font-medium text-gray-700 mb-2">Price List (RM)</p>
    <p id="price-hint" class="text-gray-500">Select a paper size below to view the price.</p>
    <div id="price-details" class="space-y-2"></div>
    <p class="text-xs text-gray-400 mt-3">*Estimated price based on number of pages × number of copies. Final price confirmed at the counter.</p>
    <p class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-200">
        Payment accepted at counter: Cash, QR Pay, or E-Wallet.
    </p>
</div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Number of Copies</label>
                    <input type="number" name="copies" min="1" max="100" value="{{ old('copies', 1) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition"
                        required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Print Color</label>
                        <select name="color_mode" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-yellow-500 outline-none">
                            <option value="black_white">Black &amp; White</option>
                            <option value="color">Color</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Orientation</label>
                        <select name="orientation" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-yellow-500 outline-none">
                            <option value="portrait">Portrait</option>
                            <option value="landscape">Landscape</option>
                        </select>
                    </div>
                </div>

                <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Paper Size</label>
    <select name="paper_size" id="paper-size" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-yellow-500 outline-none">
        <option value="" disabled {{ old('paper_size') ? '' : 'selected' }}>Select paper size</option>
        <option value="A4" @selected(old('paper_size') === 'A4')>A4</option>
        <option value="A5" @selected(old('paper_size') === 'A5')>A5</option>
        <option value="A3" @selected(old('paper_size') === 'A3')>A3</option>
    </select>
</div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Extra Services (optional)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="border rounded-lg p-3 flex items-center gap-2 cursor-pointer hover:bg-gray-50 has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                            <input type="checkbox" name="binding" value="1" class="rounded border-gray-300 text-yellow-700">
                            <span class="text-sm font-medium">Binding (+RM{{ number_format($extraServices['binding'], 2) }})</span>
                        </label>
                        <label class="border rounded-lg p-3 flex items-center gap-2 cursor-pointer hover:bg-gray-50 has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                            <input type="checkbox" name="plastic_cover" value="1" class="rounded border-gray-300 text-yellow-700">
                            <span class="text-sm font-medium">Plastic Cover (+RM{{ number_format($extraServices['plastic_cover'], 2) }})</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Request (optional)</label>
                    <textarea name="special_request" rows="2" placeholder="e.g. Staple top-left corner..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition">{{ old('special_request') }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full bg-yellow-400 text-gray-900 font-semibold py-3 rounded-lg hover:bg-yellow-500 transition shadow-sm">
            Submit Order
        </button>
    </form>

    <p class="text-center text-sm text-gray-500">
        Already submitted an order?
        <a href="{{ route('orders.track') }}" class="text-yellow-700 font-medium hover:underline">Track it here</a>
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('file-input');
    const errorEl = document.getElementById('file-error');
    const submitBtn = input.closest('form').querySelector('button[type="submit"]');
    const maxBytes = 5 * 1024 * 1024; // 5MB, matches server-side limit
    const allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'docx'];

    input.addEventListener('change', function () {
        errorEl.classList.add('hidden');
        errorEl.textContent = '';
        submitBtn.disabled = false;

        const file = input.files[0];
        if (!file) return;

        const ext = file.name.split('.').pop().toLowerCase();

        if (!allowedExt.includes(ext)) {
            errorEl.textContent = 'This file type is not allowed. Please upload PDF, JPG, PNG or DOCX only.';
            errorEl.classList.remove('hidden');
            input.value = '';
            submitBtn.disabled = true;
            return;
        }

        if (file.size > maxBytes) {
            const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
            errorEl.textContent = `This file is ${sizeMb}MB — the maximum allowed is 5MB. Please choose a smaller file.`;
            errorEl.classList.remove('hidden');
            input.value = '';
            submitBtn.disabled = true;
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const T = ['1-100', '101-500', '501-1000', '1001+'];
    const r = (labels, prices) => labels.map((l, i) => [l, prices[i]]);

    const bySize = {
        A4: [
            { t: '01 Black & white photocopy', rows: r(['1-50', '51-100', '101-1000', '1001+'], [0.20, 0.18, 0.15, 0.14]) },
            { t: '02 Black & white printing', open: true, rows: r(T, [0.50, 0.40, 0.35, 0.30]) },
            { t: '03 Color, 50% coverage', open: true, rows: r(T, [1.50, 1.20, 1.00, 0.90]) },
            { t: '03 Color, 100% coverage', open: true, rows: r(T, [2.00, 1.80, 1.50, 1.10]) },
            { t: '04 Printing paper (A4)', rows: [
                ['Glossy paper', 4.00], ['Glossy paper, kertas sendiri', 3.00],
                ['Sticker colour print', 4.00], ['Sticker colour print, kertas sendiri', 3.00],
                ['Kertas tebal 230gsm', 3.00], ['Kertas tebal 230gsm, kertas sendiri', 2.50],
                ['Kertas tebal 160gsm', 2.50], ['Kertas tebal 160gsm, kertas sendiri', 2.00],
                ['Kertas sijil', 3.00], ['Kertas sijil, kertas sendiri', 2.50],
                ['Paper colour sahaja', 0.50],
                ['Paper colour print, black & white', 1.00],
                ['Paper colour print, colour 50%', 2.00],
                ['Paper colour print, colour 100%', 2.50],
            ] },
        ],
        A3: [
            { t: '01 Black & white photocopy', rows: r(T, [0.50, 0.40, 0.35, 0.30]) },
            { t: '02 Black & white printing', open: true, rows: r(T, [1.00, 0.80, 0.60, 0.40]) },
            { t: '03 Color, 50% coverage', open: true, rows: r(T, [2.00, 1.80, 1.60, 1.50]) },
            { t: '03 Color, 100% coverage', open: true, rows: r(T, [3.00, 2.80, 2.60, 2.50]) },
        ],
    };
    bySize.A5 = bySize.A4.filter(p => !p.t.startsWith('04'));

    const common = [
        { t: '05 Binding', rows: [
            ['Staple binding', null],
            ['1-100 pages', 2.00], ['101-500 pages', 4.00], ['501+ pages', 6.00],
            ['Comb binding', null],
            ['1-100 pages', 4.00], ['101-500 pages', 6.00], ['501-1000 pages', 8.00], ['1001+ pages', 10.00],
        ] },
        { t: '06 Photo & passport (per pcs)', rows: [
            ['Passport photos (Blue | White | Red)', null],
            ['4 pcs', 15.00], ['8 pcs', 22.00], ['12 pcs', 28.00],
            ['Photo prints', null],
            ['3R, 1-50 pcs', 1.50], ['3R, 51-100 pcs', 1.40], ['3R, 101+ pcs', 1.20],
            ['4R, 1-50 pcs', 2.00], ['4R, 51-100 pcs', 1.80], ['4R, 101+ pcs', 1.50],
            ['2R | Instax | SQ120 | Namecard, 1-50 pcs', 2.00],
            ['2R | Instax | SQ120 | Namecard, 51-100 pcs', 1.80],
            ['2R | Instax | SQ120 | Namecard, 101+ pcs', 1.40],
            ['Photostrip', 3.00], ['Keychain photo', 10.00],
            ['5R', 4.00], ['6R', 6.00], ['8R', 15.00], ['8RW', 20.00], ['10R', 30.00], ['11R', 40.00],
        ] },
    ];

    const fmt = n => 'RM' + n.toFixed(2);

    function card(p) {
        const body = p.rows.map(([label, price]) => price === null
            ? `<tr><td colspan="2" class="pt-2 font-medium text-gray-700">${label}</td></tr>`
            : `<tr><td class="py-0.5">${label}</td><td class="text-right">${fmt(price)}</td></tr>`
        ).join('');

        return `<details class="border border-gray-200 rounded-lg bg-white" ${p.open ? 'open' : ''}>
            <summary class="px-3 py-2 cursor-pointer font-medium text-gray-700">${p.t}</summary>
            <div class="px-3 pb-2"><table class="w-full text-gray-600"><tbody>${body}</tbody></table></div>
        </details>`;
    }

    const select = document.getElementById('paper-size');
    const box = document.getElementById('price-details');
    const hint = document.getElementById('price-hint');

    function render() {
        const list = bySize[select.value];
        if (!list) {
            box.innerHTML = '';
            hint.classList.remove('hidden');
            return;
        }
        hint.classList.add('hidden');
        box.innerHTML = `<p class="text-xs text-gray-400">Harga untuk saiz ${select.value}</p>`
            + list.concat(common).map(card).join('');
    }

    select.addEventListener('change', render);
    render();
});
</script>
</x-guest-page>