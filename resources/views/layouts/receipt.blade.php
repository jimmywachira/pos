@props(['sale'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">
    <title>Receipt - {{ $sale->invoice_no ?? 'N/A' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
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

<body class="bg-gray-100 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100" onload="window.print()">
    {{ $slot }}
</body>
</html>
