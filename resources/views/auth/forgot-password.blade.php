<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Decathlon Admin</title>
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
                    <h2 class="text-3xl font-bold mb-4">Password Recovery</h2>
                    <p class="text-white/90 text-lg leading-relaxed">
                        Recover your administrator credentials safely. Simply input your email and we'll send a secure password reset link to your SMTP-configured mailbox.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Forgot Form -->
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

                <!-- Forgot Password Header -->
                <div class="mb-8 animate-slide-up">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Forgot Password?</h2>
                    <p class="text-gray-600">Enter your registered email address to recover your account</p>
                </div>

                <!-- Form -->
                <div class="animate-slide-up" style="animation-delay: 0.1s;">
                    <form id="forgotForm" class="space-y-5">
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

                        <!-- Buttons -->
                        <div class="space-y-4 pt-2">
                            <button 
                                type="submit"
                                id="submitBtn"
                                class="w-full bg-[#0082C3] hover:bg-[#006699] text-white font-semibold py-3.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span id="btnText">Send Recovery Link</span>
                                <span id="btnLoader" class="hidden">
                                    <i data-lucide="loader" class="animate-spin inline-block w-5 h-5"></i>
                                    Sending Link...
                                </span>
                            </button>
                            
                            <a 
                                href="{{ route('admin.login') }}" 
                                class="w-full flex items-center justify-center border-2 border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3.5 rounded-lg transition-all duration-200 text-sm shadow-sm"
                            >
                                Back to Sign In
                            </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const forgotForm = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const successMessage = document.getElementById('successMessage');
            const successText = document.getElementById('successText');
            const emailInput = document.getElementById('email');

            function validateEmail(email) {
                const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return re.test(String(email).toLowerCase());
            }

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

            forgotForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const email = emailInput.value.trim();

                errorMessage.classList.add('hidden');
                successMessage.classList.add('hidden');
                clearFieldError('email');

                if (!email) {
                    showFieldError('email', 'Email address is required.');
                    return;
                } else if (!validateEmail(email)) {
                    showFieldError('email', 'Please enter a valid email address.');
                    return;
                }

                submitBtn.disabled = true;
                btnText.classList.add('hidden');
                btnLoader.classList.remove('hidden');

                const formElements = forgotForm.querySelectorAll('input, button');
                formElements.forEach(el => el.disabled = true);

                const formData = new FormData();
                formData.append('email', email);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                fetch('{{ route("admin.forgot-password.post") }}', {
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
                    submitBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoader.classList.add('hidden');
                    formElements.forEach(el => el.disabled = false);

                    successText.textContent = data.message;
                    successMessage.classList.remove('hidden');
                    emailInput.value = '';
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    btnText.classList.remove('hidden');
                    btnLoader.classList.add('hidden');
                    formElements.forEach(el => el.disabled = false);

                    if (error && error.status === 422 && error.data && error.data.errors) {
                        showFieldError('email', error.data.errors.email[0]);
                        errorText.textContent = 'Please enter a valid email address.';
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
