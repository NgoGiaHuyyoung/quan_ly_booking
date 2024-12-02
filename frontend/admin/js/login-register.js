// Xử lý đăng nhập
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail')?.value.trim();
            const password = document.getElementById('loginPassword')?.value.trim();

            if (!email || !password) {
                alert('Please fill in all fields.');
                return;
            }

            fetch('http://localhost:5000/api/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Login successful');
                        location.reload(); // Refresh page or handle user session
                    } else {
                        alert(`Login failed: ${data.message}`);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
});

// Xử lý đăng ký
document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Lấy giá trị các trường từ form đăng ký
            const name = document.getElementById('registerName')?.value.trim();
            const username = document.getElementById('registerUsername')?.value.trim();
            const email = document.getElementById('registerEmail')?.value.trim();
            const phone = document.getElementById('registerPhone')?.value.trim();
            const password = document.getElementById('registerPassword')?.value.trim();
            const confirmPassword = document.getElementById('registerConfirmPassword')?.value.trim();
            const role = "customer"; // Mặc định là customer

            // Kiểm tra dữ liệu nhập hợp lệ
            if (!name || !username || !email || !phone || !password || !confirmPassword) {
                alert('Please fill in all fields.');
                return;
            }
            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }

            // Gửi dữ liệu đến API đăng ký
            fetch('http://localhost:5000/api/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, username, email, phone, password, role })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration successful. Please check your email for verification.');
                        location.reload(); // Refresh page or close modal
                    } else {
                        alert(`Registration failed: ${data.message}`);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
});
