<?php
// Bắt đầu session
session_start();

// Kiểm tra nếu thông tin người dùng đã được lưu trong session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $age = $_SESSION['age'];
    $gender = $_SESSION['gender'];
} else {
    // Nếu không có thông tin trong session, gán giá trị mặc định
    $username = $email = $age = $gender = "Not Set";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">User Profile</h1>
        <form id="saveProfile" class="text-center mt-4">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" placeholder="Enter username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" class="form-control" placeholder="Enter your age" value="<?php echo htmlspecialchars($age); ?>">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" class="form-control">
                    <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if ($gender == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <button type="submit" class="btn btn-dark">Save Profile to Session</button>
        </form>
        <div id="profileOutput" class="mt-4">
            <h3 class="text-center">Saved Profile</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>Username:</strong> <span id="outputUsername"><?php echo htmlspecialchars($username); ?></span></li>
                <li class="list-group-item"><strong>Email:</strong> <span id="outputEmail"><?php echo htmlspecialchars($email); ?></span></li>
                <li class="list-group-item"><strong>Age:</strong> <span id="outputAge"><?php echo htmlspecialchars($age); ?></span></li>
                <li class="list-group-item"><strong>Gender:</strong> <span id="outputGender"><?php echo htmlspecialchars($gender); ?></span></li>
            </ul>
        </div>
    </div>

    <script src="admin/js/profileUser.js"></script>
</body>
</html>
