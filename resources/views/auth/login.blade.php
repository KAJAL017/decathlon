<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Decathlon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
    </style>
</head>
<body class="bg-white min-h-screen">
    <!-- Split Screen Layout -->
    <div class="flex min-h-screen">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#0082C3] to-[#005a8c] relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 -left-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 -right-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            </div>
            
            <div class="relative z-10 flex flex-col justify-center items-center w-full px-16 text-white">
                <!-- Logo -->
                <div class="mb-12 animate-fade-in">
                    <i data-lucide="shield" class="w-20 h-20 text-white mb-6"></i>
                    <h1 class="text-5xl font-black tracking-tight">DECATHLON</h1>
                </div>
                
                <!-- Welcome Text -->
                <div class="text-center max-w-md animate-slide-up">
                    <h2 class="text-3xl font-bold mb-4">Welcome Back!</h2>
                    <p class="text-white/90 text-lg leading-relaxed">
                        Manage your e-commerce platform with powerful admin tools. Track orders, manage inventory, and grow your business.
                    </p>
                </div>

                <!-- Features -->
                <div class="mt-16 grid grid-cols-3 gap-8 w-full max-w-2xl animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">10K+</div>
                        <div class="text-white/80 text-sm">Products</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">50K+</div>
                        <div class="text-white/80 text-sm">Orders</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold mb-2">99%</div>
                        <div class="text-white/80 text-sm">Uptime</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <div class="bg-[#0082C3] p-2.5 rounded-lg">
                            <i data-lucide="shield" class="w-7 h-7 text-white"></i>
                        </div>
                        <span class="text-2xl font-black text-[#0082C3]">DECATHLON</span>
                    </div>
                </div>

                <!-- Login Header -->
                <div class="mb-8 animate-slide-up">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Admin Login</h2>
                    <p class="text-gray-600">Enter your credentials to access the dashboard</p>
                </div>

                <!-- Login Form -->
                <div class="animate-slide-up" style="animation-delay: 0.1s;">
                    <form id="loginForm" class="space-y-5">
                        @csrf
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <div class="relative" id="emailContainer">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="w-5 h-5 text-gray-400 transition-colors duration-200"></i>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    class="w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-lg focus:border-[#0082C3] focus:ring-2 focus:ring-[#0082C3]/10 outline-none transition-all text-gray-900 text-[15px]"
                                    placeholder="admin@decathlon.com"
                                >
                            </div>
                            <div id="emailErrorContainer" class="hidden flex items-center gap-1.5 mt-1.5 text-red-600 text-xs font-medium">
                                <i data-lucide="circle-alert" class="w-4 h-4 shrink-0"></i>
                                <span id="emailError"></span>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative" id="passwordContainer">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-5 h-5 text-gray-400 transition-colors duration-200"></i>
                                </div>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required
                                    class="w-full pl-12 pr-12 py-3.5 border-2 border-gray-200 rounded-lg focus:border-[#0082C3] focus:ring-2 focus:ring-[#0082C3]/10 outline-none transition-all text-gray-900 text-[15px]"
                                    placeholder="Enter your password"
                                >
                                <button 
                                    id="togglePasswordBtn"
                                    type="button" 
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0082C3] transition-colors"
                                >
                                    <i id="eye-open" data-lucide="eye" class="w-5 h-5"></i>
                                    <i id="eye-closed" data-lucide="eye-off" class="w-5 h-5 hidden"></i>
                                </button>
                            </div>
                            <div id="passwordErrorContainer" class="hidden flex items-center gap-1.5 mt-1.5 text-red-600 text-xs font-medium">
                                <i data-lucide="circle-alert" class="w-4 h-4 shrink-0"></i>
                                <span id="passwordError"></span>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" class="w-4 h-4 text-[#0082C3] border-gray-300 rounded focus:ring-[#0082C3]">
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                            <a href="{{ route('admin.forgot-password') }}" class="text-sm font-semibold text-[#0082C3] hover:text-[#006699] transition-colors">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit"
                            id="loginBtn"
                            class="w-full bg-[#0082C3] hover:bg-[#006699] text-white font-semibold py-3.5 rounded-lg transition-all duration-200 mt-6 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span id="btnText">Sign In to Dashboard</span>
                            <span id="btnLoader" class="hidden">
                                <i data-lucide="loader" class="animate-spin inline-block w-5 h-5"></i>
                                Signing In...
                            </span>
                        </button>

                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center gap-2 text-red-800 text-sm">
                                <i data-lucide="circle-x" class="w-5 h-5"></i>
                                <span id="errorText"></span>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <div id="successMessage" class="hidden mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center gap-2 text-green-800 text-sm">
                                <i data-lucide="circle-check" class="w-5 h-5"></i>
                                <span id="successText"></span>
                            </div>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="text-center mt-8 text-sm text-gray-500">
                        © 2024 Decathlon. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password Toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // AJAX Login Handler
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const successMessage = document.getElementById('successMessage');
            const successText = document.getElementById('successText');

            // Form Fields
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            // Regex for Email Validation
            function validateEmail(email) {
                const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return re.test(String(email).toLowerCase());
            }

            // Function to display field-specific error
            function showFieldError(field, message) {
                const input = document.getElementById(field);
                const errorContainer = document.getElementById(field + 'ErrorContainer');
                const errorTextElement = document.getElementById(field + 'Error');
                const wrapper = input.closest('.relative');
                const icon = wrapper ? wrapper.querySelector('i') : null;

                // Border and Focus styles
                input.classList.remove('border-gray-200', 'focus:border-[#0082C3]', 'focus:ring-[#0082C3]/10');
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/10');
                
                if (icon) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-red-500');
                }

                errorTextElement.textContent = message;
                errorContainer.classList.remove('hidden');
            }

            // Function to clear field-specific error
            function clearFieldError(field) {
                const input = document.getElementById(field);
                const errorContainer = document.getElementById(field + 'ErrorContainer');
                const wrapper = input.closest('.relative');
                const icon = wrapper ? wrapper.querySelector('i') : null;

                input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/10');
                input.classList.add('border-gray-200', 'focus:border-[#0082C3]', 'focus:ring-[#0082C3]/10');
                
                if (icon) {
                    icon.classList.remove('text-red-500');
                    icon.classList.add('text-gray-400');
                }

                errorContainer.classList.add('hidden');
            }

            // Clear errors as the user edits fields
            emailInput.addEventListener('input', () => {
                clearFieldError('email');
                errorMessage.classList.add('hidden');
            });

            passwordInput.addEventListener('input', () => {
                clearFieldError('password');
                errorMessage.classList.add('hidden');
            });

            // Handle submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const email = emailInput.value.trim();
                const password = passwordInput.value;

                // Reset previous errors
                errorMessage.classList.add('hidden');
                successMessage.classList.add('hidden');
                clearFieldError('email');
                clearFieldError('password');

                // Pre-flight Client Validation
                let hasClientError = false;
                if (!email) {
                    showFieldError('email', 'Email address is required.');
                    hasClientError = true;
                } else if (!validateEmail(email)) {
                    showFieldError('email', 'Please enter a valid email address.');
                    hasClientError = true;
                }

                if (!password) {
                    showFieldError('password', 'Password is required.');
                    hasClientError = true;
                }

                if (hasClientError) {
                    errorText.textContent = 'Please correct the errors in the form before submitting.';
                    errorMessage.classList.remove('hidden');
                    return;
                }

                // Show loader and disable form elements during request
                loginBtn.disabled = true;
                btnText.classList.add('hidden');
                btnLoader.classList.remove('hidden');

                const formElements = loginForm.querySelectorAll('input, button');
                formElements.forEach(el => {
                    if (el.id !== 'togglePasswordBtn') {
                        el.disabled = true;
                    }
                });

                // Create FormData
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                // Fetch AJAX Request
                const csrfToken = document.querySelector('input[name="_token"]').value;
                fetch('{{ route("admin.login.post") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(async response => {
                    const isJson = response.headers.get('content-type')?.includes('application/json');
                    const data = isJson ? await response.json() : null;

                    if (!response.ok) {
                        return Promise.reject({ status: response.status, data });
                    }
                    return data;
                })
                .then(data => {
                    // Show success state
                    successText.textContent = data.message || 'Login successful! Redirecting...';
                    successMessage.classList.remove('hidden');

                    // Redirect
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 800);
                })
                .catch(error => {
                    // Re-enable form elements
                    loginBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoader.classList.add('hidden');
                    formElements.forEach(el => el.disabled = false);

                    // Parse server response errors
                    if (error && error.status === 422 && error.data && error.data.errors) {
                        const errors = error.data.errors;
                        if (errors.email) {
                            showFieldError('email', errors.email[0]);
                        }
                        if (errors.password) {
                            showFieldError('password', errors.password[0]);
                        }
                        errorText.textContent = 'Validation failed. Please correct the fields listed below.';
                    } else if (error && error.status === 419) {
                        errorText.textContent = 'Session expired. Please refresh the page and try again.';
                    } else if (error && (error.status === 401 || error.status === 403) && error.data) {
                        errorText.textContent = error.data.message || 'Invalid email or password.';
                    } else {
                        errorText.textContent = 'An unexpected error occurred. Please try again.';
                        console.error('AJAX error:', error);
                    }
                    errorMessage.classList.remove('hidden');
                });
            });
        });
    </script>
</body>
</html>
