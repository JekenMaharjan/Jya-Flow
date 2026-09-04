<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Detail</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Limelight&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Monoton&family=Pinyon+Script&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="w-full h-full flex flex-col gap-5 bg-slate-900 text-slate-200 p-5">
    <h2 class="text-3xl font-mono font-bold text-center">List of Registered Users</h2>

    <div class="flex justify-center">
        <table class="font-roboto text-left border border-white w-2xl rounded-2xl">
            <thead class="border-b">
                <tr class="text-center">
                    <th class="border-r p-3">S.N.</th>
                    <th class="border-r p-3">Username</th>
                    <th class="p-3">Email</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                    <tr class="border-b">
                        <td class="border-r p-3">{{ $loop->iteration }}</td>
                        <td class="border-r p-3">{{ $user['name'] }}</td>
                        <td class="p-3">{{ $user['email'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>