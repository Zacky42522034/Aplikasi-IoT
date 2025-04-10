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