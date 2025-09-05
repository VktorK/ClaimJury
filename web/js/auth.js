// Auth JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and submission
    const forms = document.querySelectorAll('.auth-form form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            }
        });
    });

    // Real-time password strength indicator
    const passwordInputs = document.querySelectorAll('input[type="password"][name*="password"]:not([name*="repeat"])');
    
    passwordInputs.forEach(input => {
        input.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updatePasswordStrengthIndicator(this, strength);
        });
    });

    // Password visibility toggle
    const passwordFields = document.querySelectorAll('.password-field');
    passwordFields.forEach(field => {
        const input = field.querySelector('input[type="password"]');
        const toggle = field.querySelector('.password-toggle');
        
        if (toggle) {
            toggle.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('active');
            });
        }
    });

    // Form animations
    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach(group => {
        const input = group.querySelector('input, textarea, select');
        if (input) {
            input.addEventListener('focus', function() {
                group.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    group.classList.remove('focused');
                }
            });
            
            // Check if input has value on load
            if (input.value) {
                group.classList.add('focused');
            }
        }
    });
});

function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 6) strength += 1;
    if (password.length >= 10) strength += 1;
    if (/[a-z]/.test(password)) strength += 1;
    if (/[A-Z]/.test(password)) strength += 1;
    if (/[0-9]/.test(password)) strength += 1;
    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    
    return strength;
}

function updatePasswordStrengthIndicator(input, strength) {
    let indicator = input.parentNode.querySelector('.password-strength');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'password-strength';
        input.parentNode.appendChild(indicator);
    }
    
    const strengthText = ['Очень слабый', 'Слабый', 'Средний', 'Хороший', 'Сильный', 'Очень сильный'];
    const strengthColors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745', '#28a745'];
    
    indicator.textContent = strengthText[Math.min(strength, 5)];
    indicator.style.color = strengthColors[Math.min(strength, 5)];
    indicator.style.fontSize = '12px';
    indicator.style.marginTop = '5px';
    indicator.style.fontWeight = '500';
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Auto-hide alerts after 5 seconds
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }, 5000);
});
