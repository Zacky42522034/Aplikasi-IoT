<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register | My Application</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-white">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8 bg-white rounded-xl shadow-xl max-w-4xl w-full">
            <!-- Left Side with Brand and Info -->
            <div class="hidden md:flex flex-col justify-center items-center bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg p-8 text-white">
                <div class="mb-6">
                    <i class="fas fa-user-shield text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-4">Welcome Back!</h1>
                <p class="text-center mb-6">Access your account and enjoy our services with enhanced security and a seamless experience.</p>
                <div class="w-full max-w-xs">
                    <div class="flex items-center justify-center space-x-4 mb-4">
                        <button id="show-login-btn" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-opacity-90 transition w-full">Login</button>
                        <button id="show-register-btn" class="bg-transparent border border-white text-white px-4 py-2 rounded-lg font-medium hover:bg-white hover:bg-opacity-10 transition w-full">Register</button>
                    </div>
                </div>
            </div>

            <!-- Forms Container with Tabs for Mobile -->
            <div class="md:col-span-1 col-span-2">
                <!-- Tabs for Mobile -->
                <div class="flex md:hidden mb-6">
                    <button id="login-tab" class="w-1/2 py-2 font-medium border-b-2 border-blue-600 text-blue-600">Login</button>
                    <button id="register-tab" class="w-1/2 py-2 font-medium border-b-2 border-gray-200 text-gray-500">Register</button>
                </div>

                <!-- Login Form -->
                <div id="login-form" class="flex flex-col">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Sign In to Your Account</h2>
                    <form class="space-y-5" action="/postLog" method="POST">
                        @csrf
                        <div>
                            <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="login-email" class="pl-10 mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="youremail@example.com" required>
                            </div>
                        </div>
                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" name="password" id="login-password" class="pl-10 mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 cursor-pointer toggle-password"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                            </div>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-1">
                                Login
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Or continue with</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div>
                                <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fab fa-google text-red-500 mr-2"></i>
                                    Google
                                </a>
                            </div>
                            <div>
                                <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                    Facebook
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account? 
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500 md:hidden show-register">Register now</a>
                        </p>
                    </div>
                </div>

                <!-- Register Form -->
                <div id="register-form" class="flex flex-col hidden">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Account</h2>
                    <form action="{{ route('postReg') }}" method="POST" "space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="register-firstname" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="firstname" id="firstname" class="mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="John" required>
                            </div>
                            <div>
                                <label for="register-lastname" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Doe" required>
                            </div>
                        </div>
                        <div>
                            <label for="register-username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" name="username" id="username" class="pl-10 mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="johndoe" required>
                            </div>
                        </div>
                        <div>
                            <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" class="pl-10 mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="john.doe@example.com" required>
                            </div>
                        </div>
                        <div>
                            <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" name="password" id="register-password" class="pl-10 mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 cursor-pointer toggle-password"></i>
                                </div>
                            </div>
                            <div class="mt-1">
                                <div class="flex mt-1">
                                    <div class="h-1 w-1/4 bg-gray-200 rounded-l-full" id="pwd-strength-1"></div>
                                    <div class="h-1 w-1/4 bg-gray-200" id="pwd-strength-2"></div>
                                    <div class="h-1 w-1/4 bg-gray-200" id="pwd-strength-3"></div>
                                    <div class="h-1 w-1/4 bg-gray-200 rounded-r-full" id="pwd-strength-4"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Password strength: <span id="pwd-strength-text">Weak</span></p>
                            </div>
                        </div>
                        <div>
                            <label for="register-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input type="password" id="register-confirm-password" class="pl-10 mt-1 block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                            <label for="terms" class="ml-2 block text-sm text-gray-700">I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms of Service</a> and <a href="#" class="text-blue-600 hover:text-blue-500">Privacy Policy</a></label>
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-1">
                                Create Account
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Or register with</span>
                            </div>
                        </div>
                        
                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div>
                                <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fab fa-google text-red-500 mr-2"></i>
                                    Google
                                </a>
                            </div>
                            <div>
                                <a href="#" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="fab fa-facebook text-blue-600 mr-2"></i>
                                    Facebook
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500 md:hidden show-login">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Toggle between login and register forms
document.addEventListener('DOMContentLoaded', function() {
    // Mobile tabs
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    
    // Desktop buttons
    const showLoginBtn = document.getElementById('show-login-btn');
    const showRegisterBtn = document.getElementById('show-register-btn');
    
    // Mobile switch links
    const showLoginLinks = document.querySelectorAll('.show-login');
    const showRegisterLinks = document.querySelectorAll('.show-register');
    
    // Toggle password visibility
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    
    function showLogin() {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('border-indigo-600', 'text-indigo-600');
        loginTab.classList.remove('border-gray-200', 'text-gray-500');
        registerTab.classList.add('border-gray-200', 'text-gray-500');
        registerTab.classList.remove('border-indigo-600', 'text-indigo-600');
    }
    
    function showRegister() {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        registerTab.classList.add('border-indigo-600', 'text-indigo-600');
        registerTab.classList.remove('border-gray-200', 'text-gray-500');
        loginTab.classList.add('border-gray-200', 'text-gray-500');
        loginTab.classList.remove('border-indigo-600', 'text-indigo-600');
    }
    
    // Event listeners
    loginTab.addEventListener('click', showLogin);
    registerTab.addEventListener('click', showRegister);
    
    if (showLoginBtn) showLoginBtn.addEventListener('click', showLogin);
    if (showRegisterBtn) showRegisterBtn.addEventListener('click', showRegister);
    
    showLoginLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            showLogin();
        });
    });
    
    showRegisterLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            showRegister();
        });
    });
    
    // Toggle password visibility
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('div').querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
    
    // Password strength indicator
    const passwordInput = document.getElementById('register-password');
    const strengthBars = [
        document.getElementById('pwd-strength-1'),
        document.getElementById('pwd-strength-2'),
        document.getElementById('pwd-strength-3'),
        document.getElementById('pwd-strength-4')
    ];
    const strengthText = document.getElementById('pwd-strength-text');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        if (password.length > 6) strength++;
        if (password.length > 10) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Normalize strength to be between 0-4
        strength = Math.min(4, strength);
        
        // Update bars
        for (let i = 0; i < 4; i++) {
            if (i < strength) {
                strengthBars[i].classList.remove('bg-gray-200');
                
                if (strength === 1) strengthBars[i].classList.add('bg-red-500');
                else if (strength === 2) strengthBars[i].classList.add('bg-orange-500');
                else if (strength === 3) strengthBars[i].classList.add('bg-yellow-500');
                else strengthBars[i].classList.add('bg-green-500');
            } else {
                strengthBars[i].className = 'h-1 bg-gray-200';
                if (i === 0) strengthBars[i].classList.add('rounded-l-full');
                if (i === 3) strengthBars[i].classList.add('rounded-r-full');
            }
        }
        
        // Update text
        if (strength === 0) strengthText.textContent = 'Weak';
        else if (strength === 1) strengthText.textContent = 'Weak';
        else if (strength === 2) strengthText.textContent = 'Fair';
        else if (strength === 3) strengthText.textContent = 'Good';
        else strengthText.textContent = 'Strong';
    });
});
    </script>

@if(session('success'))
<script>
    Swal.fire({
        title: "Registrasi Berhasil!",
        text: "{{ session('success') }}",
        icon: "success",
        confirmButtonText: "OK"
    });
</script>
@endif
</body>
</html>