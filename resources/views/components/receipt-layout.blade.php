@props(['sale'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Receipt - {{ $sale->invoice_no ?? 'N/A' }}</title> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Google Sans Code', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                /* Chrome, Safari */
                color-adjust: exact;
                /* Firefox */
            }
        }

    </style>
</head>

<body class="bg-gray-100" onload="window.print()">
    {{ $slot }}
</body>
</html>
