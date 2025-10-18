<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Receipt - {{ $sale->invoice_no }}</title> --}}
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }

    </style>
</head>
<body class="bg-gray-100" onload="window.print()">
    {{ $slot }}
</body>
</html>
