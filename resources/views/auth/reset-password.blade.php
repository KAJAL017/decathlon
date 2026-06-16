<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Decathlon Admin</title>
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
                    <i data-lucide="lock" class="w-20 h-20 text-white mb-6"></i>
                    <h1 class="text-5xl font-black tracking-tight">DECATHLON</h1>
                </div>
                
                <!-- Info Text -->
                <div class="text-center max-w-md animate-slide-up">
                    <h2 class="text-3xl font-bold mb-4">Set New Password</h2>
                    <p class="text-white/90 text-lg leading-relaxed">
                        Ensure your account is protected with a highly secure password. Choose a combination of letters, numbers, and symbols that is easy to remember but hard to guess.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Reset Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <div class="bg-[#0082C3] p-2.5 rounded-lg">
                            <i data-lucide="lock" class="w-7 h-7 text-white"></i>
                        </div>
                        <span class="text-2xl font-black text-[#0082C3]">DECATHLON</span>
                    </div>
                </div>

                <!-- Reset Password Header -->
                <div class="mb-8 animate-slide-up">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
                    <p class="text-gray-600">Enter your credentials and choose a strong new password</p>
                </div>

                <!-- Form -->
                <div class="animate-slide-up" style="animation-delay: 0.1s;">
                    <form id="resetForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="token" id="token" value="{{ $token }}">

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
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
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
                                    placeholder="Minimum 6 characters"
                                >
                                <button 
                                    id="togglePasswordBtn"
                                    type="button" 
                                    onclick="togglePassword('password', 'eye-open-pass', 'eye-closed-pass')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0082C3] transition-colors"
                                >
                                    <i id="eye-open-pass" data-lucide="eye" class="w-5 h-5"></i>
                                    <i id="eye-closed-pass" data-lucide="eye-off" class="w-5 h-5 hidden"></i>
                                </button>
                            </div>
                            <div id="passwordErrorContainer" class="hidden flex items-center gap-1.5 mt-1.5 text-red-600 text-xs font-medium">
                                <i data-lucide="circle-alert" class="w-4 h-4 shrink-0"></i>
                                <span id="passwordError"></span>
                            </div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                            <div class="relative" id="password_confirmationContainer">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-5 h-5 text-gray-400 transition-colors duration-200"></i>
                                </div>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                    class="w-full pl-12 pr-12 py-3.5 border-2 border-gray-200 rounded-lg focus:border-[#0082C3] focus:ring-2 focus:ring-[#0082C3]/10 outline-none transition-all text-gray-900 text-[15px]"
                                    placeholder="Repeat your new password"
                                >
                                <button 
                                    id="togglePasswordConfirmationBtn"
                                    type="button" 
                                    onclick="togglePassword('password_confirmation', 'eye-open-conf', 'eye-closed-conf')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#0082C3] transition-colors"
                                >
                                    <i id="eye-open-conf" data-lucide="eye" class="w-5 h-5"></i>
                                    <i id="eye-closed-conf" data-lucide="eye-off" class="w-5 h-5 hidden"></i>
                                </button>
                            </div>
                            <div id="password_confirmationErrorContainer" class="hidden flex items-center gap-1.5 mt-1.5 text-red-600 text-xs font-medium">
                                <i data-lucide="circle-alert" class="w-4 h-4 shrink-0"></i>
                                <span id="password_confirmationError"></span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-4 pt-2">
                            <button 
                                type="submit"
                                id="submitBtn"
                                class="w-full bg-[#0082C3] hover:bg-[#006699] text-white font-semibold py-3.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span id="btnText">Save & Reset Password</span>
                                <span id="btnLoader" class="hidden">
                                    <i data-lucide="loader" class="animate-spin inline-block w-5 h-5"></i>
                                    Saving Password...
                                </span>
                            </button>
                        </div>

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
        function togglePassword(inputId, openId, closedId) {
            const input = document.getElementById(inputId);
            const openSvg = document.getElementById(openId);
            const closedSvg = document.getElementById(closedId);
            
            if (input.type === 'password') {
                input.type = 'text';
                openSvg.classList.add('hidden');
                closedSvg.classList.remove('hidden');
            } else {
                input.type = 'password';
                openSvg.classList.remove('hidden');
                closedSvg.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const resetForm = document.getElementById('resetForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const successMessage = document.getElementById('successMessage');
            const successText = document.getElementById('successText');

            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');
            const tokenInput = document.getElementById('token');

            function showFieldError(field, message) {
                const input = document.getElementById(field);
                const errorContainer = document.getElementById(field + 'ErrorContainer');
                const errorTextElement = document.getElementById(field + 'Error');
                const wrapper = input.closest('.relative');
                const icon = wrapper ? wrapper.querySelector('i') : null;

                input.classList.remove('border-gray-200', 'focus:border-[#0082C3]', 'focus:ring-[#0082C3]/10');
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/10');
                
                if (icon) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-red-500');
                }

                errorTextElement.textContent = message;
                errorContainer.classList.remove('hidden');
            }

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

            emailInput.addEventListener('input', () => {
                clearFieldError('email');
                errorMessage.classList.add('hidden');
            });
            passwordInput.addEventListener('input', () => {
                clearFieldError('password');
                errorMessage.classList.add('hidden');
            });
            confirmInput.addEventListener('input', () => {
                clearFieldError('password_confirmation');
                errorMessage.classList.add('hidden');
            });

            resetForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const email = emailInput.value.trim();
                const password = passwordInput.value;
                const confirmation = confirmInput.value;
                const token = tokenInput.value;

                errorMessage.classList.add('hidden');
                successMessage.classList.add('hidden');
                clearFieldError('email');
                clearFieldError('password');
                clearFieldError('password_confirmation');

                let hasClientError = false;

                if (!email) {
                    showFieldError('email', 'Email address is required.');
                    hasClientError = true;
                }
                if (!password) {
                    showFieldError('password', 'New password is required.');
                    hasClientError = true;
                } else if (password.length < 6) {
                    showFieldError('password', 'Password must be at least 6 characters.');
                    hasClientError = true;
                }
                if (!confirmation) {
                    showFieldError('password_confirmation', 'Confirm password is required.');
                    hasClientError = true;
                } else if (password !== confirmation) {
                    showFieldError('password_confirmation', 'Passwords do not match.');
                    hasClientError = true;
                }

                if (hasClientError) {
                    return;
                }

                submitBtn.disabled = true;
                btnText.classList.add('hidden');
                btnLoader.classList.remove('hidden');

                const formElements = resetForm.querySelectorAll('input, button');
                formElements.forEach(el => {
                    if (el.id !== 'togglePasswordBtn' && el.id !== 'togglePasswordConfirmationBtn') {
                        el.disabled = true;
                    }
                });

                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                formData.append('password_confirmation', confirmation);
                formData.append('token', token);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route("admin.reset-password.post") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
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
                    successText.textContent = data.message;
                    successMessage.classList.remove('hidden');

                    setTimeout(() => {
                        window.location.href = '{{ route("admin.login") }}';
                    }, 1500);
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoader.classList.add('hidden');
                    formElements.forEach(el => el.disabled = false);

                    if (error && error.status === 422 && error.data && error.data.errors) {
                        const errors = error.data.errors;
                        if (errors.email) showFieldError('email', errors.email[0]);
                        if (errors.password) showFieldError('password', errors.password[0]);
                        if (errors.token) errorText.textContent = errors.token[0];
                        else errorText.textContent = 'Validation failed. Please correct the fields listed below.';
                    } else if (error && error.data && error.data.message) {
                        errorText.textContent = error.data.message;
                    } else {
                        errorText.textContent = 'An unexpected error occurred. Please try again.';
                    }
                    errorMessage.classList.remove('hidden');
                });
            });
        });
    </script>
</body>
</html>
