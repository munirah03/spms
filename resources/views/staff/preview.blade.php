<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preview — {{ $order->order_id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #2d2d2d;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .toolbar {
            background: #1a1a1a;
            color: white;
            padding: 12px 20px;
            font-family: sans-serif;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .toolbar a {
            color: #93c5fd;
            text-decoration: none;
        }
        .viewer-wrap {
            flex: 1;
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        img {
            max-width: 100%;
            height: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            user-select: none;
            -webkit-user-drag: none;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .no-preview {
            color: white;
            font-family: sans-serif;
            text-align: center;
            padding: 20px;
            font-size: 15px;
            max-width: 400px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 40px;
            color: rgba(255,255,255,0.06);
            font-family: sans-serif;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
        }
    </style>
</head>
<body oncontextmenu="return false;">

    <div class="toolbar">
        <span>Preview: {{ $order->order_id }} — View only, printing disabled</span>
        <a href="javascript:window.close()">Close</a>
    </div>

    <div class="watermark">STAFF PREVIEW ONLY — {{ $order->order_id }}</div>

    <div class="viewer-wrap">
        @php $ext = strtolower($order->file_type); @endphp

        @if(in_array($ext, ['jpg', 'jpeg', 'png']))
    <img src="{{ $fileUrl }}" alt="Document Preview" draggable="false">
@else
    <iframe src="{{ $fileUrl }}#toolbar=0&navpanes=0&scrollbar=0"></iframe>
@endif
    </div>

    <script>
        document.addEventListener('keydown', function (e) {
            if (
                (e.ctrlKey && (e.key === 'p' || e.key === 's' || e.key === 'P' || e.key === 'S')) ||
                e.key === 'PrintScreen'
            ) {
                e.preventDefault();
                alert('Printing and saving is disabled in preview mode.');
            }
        });
    </script>

</body>
</html>