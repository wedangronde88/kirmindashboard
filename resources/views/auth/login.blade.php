<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - KIRIMINDashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN for quick styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-center mb-8 text-blue-900">Selamat datang di <span class="text-blue-600">KIRIMINDashboard</span></h1>
        <div class="bg-white shadow-lg rounded-lg p-8">
            @if(session('error'))
                <div class="mb-4 text-red-600 text-center">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('username')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Login</button>
            </form>
            <div class="flex justify-between mt-6 text-sm">
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
                <a href="#" class="text-blue-600 hover:underline">Forgot my password?</a>
            </div>
        </div>
    </div>
</body>
</html>