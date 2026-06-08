@extends('layouts.app')

@section('title', 'Create Account - Decathlon')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h2 class="text-center text-3xl font-black text-gray-900 uppercase tracking-tight">
                Join Us
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-[#0082C3] hover:underline">
                    Sign in
                </a>
            </p>
        </div>
        <form id="register-form" class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">First Name</label>
                        <input id="first_name" name="first_name" type="text" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                            placeholder="John">
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required
                            class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                            placeholder="Doe">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="john@example.com">
                </div>
                <div>
                    <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 rounded-lg focus:outline-none focus:ring-[#0082C3] focus:border-[#0082C3] focus:z-10 sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="accepts_marketing" name="accepts_marketing" type="checkbox" value="1"
                        class="h-4 w-4 text-[#0082C3] focus:ring-[#0082C3] border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="accepts_marketing" class="font-medium text-gray-700">Keep me updated with latest sports offers and events.</label>
                </div>
            </div>

            <div class="text-xs text-gray-500 text-center px-4">
                By clicking "Join Us", you agree to our <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Privacy Policy</a>.
            </div>

            <div>
                <button type="submit" id="register-submit"
                    class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-black uppercase tracking-widest rounded-lg text-white bg-[#0082C3] hover:bg-[#006699] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0082C3] transition-all">
                    <span class="submit-text">Join Us</span>
                    <span class="loading-spinner hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('register-submit');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.loading-spinner');
        
        // Disable button and show spinner
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        spinner.classList.remove('hidden');
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Toastify({
                    text: data.message,
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#10b981",
                }).showToast();
                
                setTimeout(() => {
                    window.location.href = data.redirect || "{{ route('home') }}";
                }, 1000);
            } else {
                Toastify({
                    text: data.message || "Validation failed.",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#ef4444",
                }).showToast();
                
                // Reset button
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Toastify({
                text: "An error occurred. Please try again.",
                duration: 3000,
                gravity: "top",
                position: "center",
                backgroundColor: "#ef4444",
            }).showToast();
            
            // Reset button
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            spinner.classList.add('hidden');
        });
    });
</script>
@endsection
