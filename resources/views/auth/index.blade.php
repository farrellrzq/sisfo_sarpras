<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Admin Portal</h1>
            <p class="text-gray-600 mt-2">Administrator access only</p>
        </div>

        <form action="/admin/login" method="POST">
            @csrf <!-- Laravel CSRF token -->

            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Admin Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="admin@example.com"
                        required
                        oninvalid="this.setCustomValidity('Please enter a valid admin email')"
                        oninput="this.setCustomValidity('')"
                    >
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Admin Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••"
                        required
                        minlength="8"
                        oninvalid="this.setCustomValidity('Password must be at least 8 characters')"
                        oninput="this.setCustomValidity('')"
                    >
                </div>
            </div>

            <!-- Admin Secret Key (optional additional security) -->
            <div class="mb-6">
                <label for="admin_key" class="block text-gray-700 text-sm font-medium mb-2">Admin Secret Key</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-key text-gray-400"></i>
                    </div>
                    <input
                        type="password"
                        id="admin_key"
                        name="admin_key"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter admin key"
                        required
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150"
            >
                Login as Admin
            </button>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mt-4 p-3 bg-red-50 text-red-700 rounded-md text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Admin Access Warning -->
            <div class="mt-4 p-3 bg-yellow-50 text-yellow-700 rounded-md text-sm">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                This portal is restricted to authorized administrators only.
            </div>
        </form>
    </div>

    <!-- Client-side validation script -->
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;

            // Simple client-side validation for admin email (you should do proper validation on server-side)
            if (!email.endsWith('@admin.com')) { // Change this to your admin email pattern
                e.preventDefault();
                alert('Only admin emails are allowed to login');
                return false;
            }

            // Additional validations can be added here
        });
    </script>
</body>
</html>
