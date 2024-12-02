<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 400px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { background-color: #007BFF; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Email Verification</h2>
        <form id="verificationForm">
            <div class="form-group">
                <label for="verificationCode">Enter Verification Code</label>
                <input type="text" id="verificationCode" placeholder="Verification Code" required>
            </div>
            <button type="submit">Verify Email</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const verificationForm = document.getElementById('verificationForm');
            verificationForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const verificationCode = document.getElementById('verificationCode').value.trim();

                if (!verificationCode) {
                    alert('Please enter the verification code.');
                    return;
                }

                // Get email and verification code from URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const email = urlParams.get('email');
                const code = urlParams.get('code');

                // Validate email and verification code
                if (!email || !code) {
                    alert('Invalid verification link. Please check your email.');
                    return;
                }

                // Send verification data to backend
                fetch('process-verification.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email, code: verificationCode })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Email verified successfully!');
                            window.location.href = 'login.php'; // Redirect to login page
                        } else {
                            alert(`Verification failed: ${data.message}`);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>
