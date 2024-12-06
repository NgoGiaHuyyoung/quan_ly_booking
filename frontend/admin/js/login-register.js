document.addEventListener('DOMContentLoaded', function () {
    // Khởi tạo các modal
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
    const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));

    const loginLink = document.getElementById('loginLink');
    const registerLink = document.getElementById('registerLink');
    const logoutLink = document.getElementById('logoutLink');

    // Hàm cập nhật các nút trên header
    function updateHeaderButtons() {
        const token = sessionStorage.getItem('token');
        if (token) {
            loginLink.style.display = 'none';
            registerLink.style.display = 'none';
            logoutLink.style.display = 'block';
        } else {
            loginLink.style.display = 'block';
            registerLink.style.display = 'block';
            logoutLink.style.display = 'none';
        }
    }

    // Gọi hàm cập nhật ngay khi trang tải
    updateHeaderButtons();

    // Xử lý đăng nhập
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
                        sessionStorage.setItem('token', data.token);
                        sessionStorage.setItem('user', JSON.stringify(data.user));
                        loginModal.hide();
                        registerModal.hide();
                        updateHeaderButtons();
                    } else {
                        alert(`Login failed: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        });
    }

    // Xử lý đăng ký
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const name = document.getElementById('registerName')?.value.trim();
            const username = document.getElementById('registerUsername')?.value.trim();
            const email = document.getElementById('registerEmail')?.value.trim();
            const phone = document.getElementById('registerPhone')?.value.trim();
            const password = document.getElementById('registerPassword')?.value.trim();
            const confirmPassword = document.getElementById('registerConfirmPassword')?.value.trim();
            const role = "customer";

            if (!name || !username || !email || !phone || !password || !confirmPassword) {
                alert('Please fill in all fields.');
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }

            fetch('http://localhost:5000/api/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, username, email, phone, password, role })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration successful. Please check your email for verification.');
                        registerModal.hide();
                    } else {
                        alert(`Registration failed: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during registration. Please try again.');
                });
        });
    }

    // Xử lý đăng xuất
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            const token = sessionStorage.getItem('token');

            if (!token) {
                alert('No token found. You are not logged in.');
                return;
            }

            fetch('http://localhost:5000/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Logout successful');
                        sessionStorage.removeItem('token');
                        sessionStorage.removeItem('user');
                        updateHeaderButtons();
                    } else {
                        alert('Logout failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error during logout:', error);
                    alert('An error occurred during logout. Please try again.');
                });
        });
    }
});
