{{-- filepath: /Users/marufhasan/Herd/form/resources/views/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
    @vite(['resources/js/app.js'])
<head>
    @inertiaHead
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @inertia
</body>
</html>