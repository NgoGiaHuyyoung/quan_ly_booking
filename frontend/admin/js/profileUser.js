document.addEventListener('DOMContentLoaded', function () {
    // Lấy thông tin người dùng từ sessionStorage
    const user = JSON.parse(sessionStorage.getItem('user'));

    // Kiểm tra nếu có thông tin người dùng trong sessionStorage
    if (user) {
        // Hiển thị thông tin người dùng trong các trường input
        document.getElementById('username').value = user.username;
        document.getElementById('email').value = user.email;
        document.getElementById('age').value = user.age || '';
        document.getElementById('gender').value = user.gender || 'male';

        // Hiển thị phần thông tin người dùng đã lưu vào profileOutput
        document.getElementById('outputUsername').textContent = user.username;
        document.getElementById('outputEmail').textContent = user.email;
        document.getElementById('outputAge').textContent = user.age || '';
        document.getElementById('outputGender').textContent = user.gender || 'male';

        // Hiển thị phần profileOutput
        document.getElementById('profileOutput').style.display = 'block';
    } else {
        alert('No user data found in session.');
    }

    // Lưu thông tin mới vào sessionStorage khi người dùng nhấn nút "Save Profile to Session"
    const saveProfileForm = document.getElementById('saveProfile');
    if (saveProfileForm) {
        saveProfileForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Lấy giá trị từ các trường input
            const updatedUser = {
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                age: document.getElementById('age').value,
                gender: document.getElementById('gender').value
            };

            // Lưu thông tin mới vào sessionStorage
            sessionStorage.setItem('user', JSON.stringify(updatedUser));

            // Cập nhật lại phần thông tin hiển thị
            document.getElementById('outputUsername').textContent = updatedUser.username;
            document.getElementById('outputEmail').textContent = updatedUser.email;
            document.getElementById('outputAge').textContent = updatedUser.age;
            document.getElementById('outputGender').textContent = updatedUser.gender;

            alert('Profile saved successfully!');
        });
    }

    // Xử lý thay đổi thông tin người dùng và cập nhật trong backend
    const updateProfileBtn = document.getElementById('updateProfileBtn');
    if (updateProfileBtn) {
        updateProfileBtn.addEventListener('click', async function () {
            const updatedUser = {
                username: document.getElementById('username').value,
                email: document.getElementById('email').value,
                age: document.getElementById('age').value,
                gender: document.getElementById('gender').value
            };

            const accessToken = sessionStorage.getItem('accessToken');
            if (!accessToken) {
                alert('You are not logged in.');
                return;
            }

            try {
                const response = await fetch('http://localhost:5000/api/user/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${accessToken}`
                    },
                    body: JSON.stringify(updatedUser)
                });
                const data = await response.json();

                if (data.success) {
                    // Lưu thông tin đã cập nhật vào sessionStorage
                    sessionStorage.setItem('user', JSON.stringify(updatedUser));
                    alert('Profile updated successfully!');
                } else {
                    alert('Update failed: ' + data.message);
                }
            } catch (error) {
                console.error('Error during profile update:', error);
                alert('An error occurred while updating your profile.');
            }
        });
    }
});
