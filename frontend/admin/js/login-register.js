document.addEventListener('DOMContentLoaded', function () {
    // Khởi tạo các modal
    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
    const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));

    const loginLink = document.getElementById('loginLink');
    const registerLink = document.getElementById('registerLink');
    const logoutLink = document.getElementById('logoutLink');

    // Hàm cập nhật hiển thị nút trong header
    function updateHeaderButtons() {
        const accessToken = sessionStorage.getItem('accessToken'); // Lấy accessToken từ sessionStorage
        if (accessToken) {
            loginLink.style.display = 'none';
            registerLink.style.display = 'none';
            logoutLink.style.display = 'block';
        } else {
            loginLink.style.display = 'block';
            registerLink.style.display = 'block';
            logoutLink.style.display = 'none';
        }
    }

    // Cập nhật ngay khi tải trang
    updateHeaderButtons();

    // Xử lý sự kiện đăng nhập
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail')?.value.trim();
            const password = document.getElementById('loginPassword')?.value.trim();

            if (!email || !password) {
                alert('Please fill in all fields.');
                return;
            }

            try {
                const response = await fetch('http://localhost:5000/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();

                if (data.success) {
                    alert('Login successful!');
                    // Lưu accessToken và refreshToken vào sessionStorage
                    sessionStorage.setItem('accessToken', data.accessToken);
                    sessionStorage.setItem('refreshToken', data.refreshToken);
                    sessionStorage.setItem('user', JSON.stringify(data.user));
                    loginModal.hide();
                    updateHeaderButtons(); // Cập nhật lại nút hiển thị trong header sau khi đăng nhập
                } else {
                    alert(`Login failed: ${data.message}`);
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    }

    // Xử lý sự kiện đăng ký
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const name = document.getElementById('registerName')?.value.trim();
            const username = document.getElementById('registerUsername')?.value.trim();
            const email = document.getElementById('registerEmail')?.value.trim();
            const phone = document.getElementById('registerPhone')?.value.trim();
            const password = document.getElementById('registerPassword')?.value.trim();
            const confirmPassword = document.getElementById('registerConfirmPassword')?.value.trim();

            if (!name || !username || !email || !phone || !password || !confirmPassword) {
                alert('Please fill in all fields.');
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }

            try {
                const response = await fetch('http://localhost:5000/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, username, email, phone, password, role: 'customer' })
                });
                const data = await response.json();

                if (data.success) {
                    alert('Registration successful! Please verify your email.');
                    registerModal.hide();
                } else {
                    alert(`Registration failed: ${data.message}`);
                }
            } catch (error) {
                console.error('Registration error:', error);
                alert('An error occurred during registration. Please try again.');
            }
        });
    }

    // Xử lý đăng xuất
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            const accessToken = sessionStorage.getItem('accessToken');

            if (!accessToken) {
                alert('No token found. You are not logged in.');
                return;
            }

            fetch('http://localhost:5000/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${accessToken}`
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Logout successful');
                        // Xóa accessToken và refreshToken
                        sessionStorage.removeItem('accessToken');
                        sessionStorage.removeItem('refreshToken');
                        sessionStorage.removeItem('user');
                        updateHeaderButtons(); // Cập nhật lại nút hiển thị trong header sau khi đăng xuất
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
