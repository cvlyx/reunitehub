document.addEventListener('DOMContentLoaded', () => {
    const reportForm = document.getElementById('reportForm');
    if (reportForm) {
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = reportForm.querySelector('[x-ref="imagePreview"]');
        imageUpload.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.querySelector('img').src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        reportForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(reportForm);
            const submitButton = reportForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
            try {
                const response = await fetch('/api/report_item.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    reportForm.reset();
                    imagePreview.classList.add('hidden');
                } else {
                    alert('Error: ' + result.error);
                    const errors = reportForm.__x.$data.errors;
                    errors[result.error_field || 'general'] = result.error;
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Submit Report';
            }
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            formData.append('action', 'login');
            const submitButton = loginForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Logging in...';
            try {
                const response = await fetch('/api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    window.location.href = 'index.php';
                } else {
                    alert('Error: ' + result.error);
                    const errors = loginForm.__x.$data.errors;
                    errors[result.error_field || 'general'] = result.error;
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Login';
            }
        });
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            formData.append('action', 'register');
            const submitButton = registerForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Registering...';
            try {
                const response = await fetch('/api/auth.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.success) {
                    window.location.href = 'index.php';
                } else {
                    alert('Error: ' + result.error);
                    const errors = registerForm.__x.$data.errors;
                    errors[result.error_field || 'general'] = result.error;
                }
            } catch (error) {
                alert('Network error: ' + error.message);
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Register';
            }
        });
    }
});