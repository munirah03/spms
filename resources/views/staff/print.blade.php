<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print — {{ $order->order_id }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f3f4f6;
            font-family: Arial, sans-serif;
        }

        /* TOP BAR */
        .print-bar {
            height: 62px;
            background: #1f2937;
            padding: 12px 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            position: sticky;
            top: 0;
            z-index: 9999;
        }

        .print-bar span {
            color: white;
            font-size: 14px;
            font-weight: 600;
        }

        .print-bar button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }

        .print-bar button:hover {
            background: #1d4ed8;
        }

        /* DOCUMENT AREA */
        .file-wrap {
            width: 100%;
            min-height: calc(100vh - 62px);
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        /* IMAGE */
        .image-document {
            max-width: 100%;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .image-document img {
            max-width: 100%;
            height: auto;
            display: block;
            user-select: none;
            -webkit-user-drag: none;
        }

        /* PDF PAGES */
        .pdf-page {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            margin-bottom: 20px;
        }

        .pdf-page canvas {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .loading {
            color: #374151;
            padding: 30px;
            font-size: 15px;
        }

        /* PRINT */
        @media print {

            .print-bar {
                display: none !important;
            }

            body {
                background: white;
            }

            .file-wrap {
                padding: 0;
                display: block;
            }

            .image-document {
                box-shadow: none;
                margin: 0;
                page-break-after: always;
            }

            .image-document img {
                width: 100%;
                max-width: 100%;
            }

            .pdf-page {
                box-shadow: none;
                margin: 0;
                padding: 0;
                page-break-after: always;
            }

            .pdf-page canvas {
                width: 100% !important;
                height: auto !important;
            }

            .loading {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- PRINT BAR -->
    <div class="print-bar">
        <span>
            {{ $order->order_id }} — Ready to print
        </span>

        <button id="printBtn" type="button">
            🖨️ Click to Print
        </button>
    </div>


    @php
        $ext = strtolower($order->file_type);
    @endphp


    <!-- DOCUMENT -->
    <div class="file-wrap" id="documentArea">

        @if(in_array($ext, ['jpg', 'jpeg', 'png']))

            <!-- IMAGE DOCUMENT -->

            <div class="image-document">
                <img
                    id="printImage"
                    src="{{ $fileUrl }}"
                    alt="Document"
                    draggable="false"
                >
            </div>

        @elseif($ext === 'pdf')

            <!-- PDF DOCUMENT -->

            <div id="pdfContainer">
                <div class="loading">
                    Loading document...
                </div>
            </div>

        @else

            <div class="loading">
                This file type cannot be displayed for printing.
            </div>

        @endif

    </div>


    @if($ext === 'pdf')

    <!-- PDF.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

    <script>

        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfUrl = @json($fileUrl);
        const pdfContainer = document.getElementById('pdfContainer');

        async function loadPDF() {

            try {

                const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

                pdfContainer.innerHTML = '';

                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {

                    const page = await pdf.getPage(pageNumber);

                    const viewport = page.getViewport({
                        scale: 1.5
                    });

                    const pageDiv = document.createElement('div');

                    pageDiv.className = 'pdf-page';

                    const canvas = document.createElement('canvas');

                    const context = canvas.getContext('2d');

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    pageDiv.appendChild(canvas);

                    pdfContainer.appendChild(pageDiv);

                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;
                }

            } catch (error) {

                console.error(error);

                pdfContainer.innerHTML = `
                    <div class="loading">
                        Unable to load the document.
                    </div>
                `;
            }
        }

        loadPDF();

    </script>

    @endif


    <!-- PRINT SCRIPT -->
    <script>

        const printBtn = document.getElementById('printBtn');

        printBtn.addEventListener('click', function () {

            // Small delay to make sure document is rendered
            setTimeout(function () {

                window.print();

            }, 300);

        });


        /* Disable right click */
        document.getElementById('documentArea')
            .addEventListener('contextmenu', function (event) {

                event.preventDefault();

            });


        /* Disable dragging */
        document.querySelectorAll('img').forEach(function (img) {

            img.addEventListener('dragstart', function (event) {

                event.preventDefault();

            });

        });

    </script>

</body>
</html>