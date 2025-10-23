<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    {{-- <title>Receipt #{{ $sale->id }}</title> --}}
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
        }

        .center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td,
        th {
            padding: 4px;
            border-bottom: 1px dashed #ccc;
        }

        .right {
            text-align: right;
        }

    </style>
</head>
<body class="bg-gray-100" onload="window.print()">
    {{ $slot }}
</body>
</html>
