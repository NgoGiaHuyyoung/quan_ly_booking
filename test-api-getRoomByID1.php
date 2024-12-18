<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test Fetch Room by ID</title>
</head>

<body>
  <h1>Fetch Room by ID</h1>
  <label for="roomId">Room ID:</label>
  <input type="text" id="roomId" placeholder="Enter Room ID">
  <button id="fetchBtn">Fetch Room Data</button>
  <div id="roomData"></div> <!-- Nơi hiển thị thông tin phòng -->
  <pre id="output"></pre>

  <script>
    document.getElementById('fetchBtn').addEventListener('click', () => {
      const roomId = document.getElementById('roomId').value;
      const output = document.getElementById('output');
      const roomData = document.getElementById('roomData');
      
      if (!roomId) {
        output.textContent = 'Please enter a valid Room ID.';
        return;
      }

      output.textContent = 'Fetching...';
      roomData.innerHTML = ''; // Clear previous data

      // Fetch room data from the server by Room ID
      fetch(`http://localhost:5000/api/rooms/${roomId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        })
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          if (data.message) {
            output.textContent = `Error: ${data.message}`; // Display error message from API (like room not found or invalid ID)
            console.error('Error:', data.message);
          } else {
            output.textContent = 'Data fetched successfully!';
            console.log('Success:', data);
            displayRoomData(data); // Update to use data directly as it should return room data in the response body
          }
        })
        .catch((error) => {
          output.textContent = `Error: ${error.message}`;
          console.error('Fetch error:', error.message);
        });
    });

    // Function to display room data in a table
    function displayRoomData(room) {
      const roomDataDiv = document.getElementById('roomData');
      const table = document.createElement('table');
      table.border = '1';
      table.style.marginTop = '20px';
      table.style.width = '100%';

      // Table header
      const headerRow = document.createElement('tr');
      const headers = ['Tên Phòng', 'Loại Phòng', 'Giá', 'Trạng Thái', 'Số Lượng Còn Trống', 'Hình Ảnh'];
      headers.forEach(headerText => {
        const th = document.createElement('th');
        th.textContent = headerText;
        headerRow.appendChild(th);
      });
      table.appendChild(headerRow);

      // Room data
      const row = document.createElement('tr');

      // Check if images exists and display the first image or fallback to 'No Image'
      const imageHTML = (room.images && room.images.length > 0) ? 
        `<img src="http://localhost:5000${room.images[0]}" alt="Room Image" width="100">` :
        'No Image'; // Default if no image

      row.innerHTML = `
        <td>${room.name}</td>
        <td>${room.type}</td>
        <td>${room.price}</td>
        <td>${room.status}</td>
        <td>${room.availableQuantity}</td>
        <td>${imageHTML}</td>
      `;
      table.appendChild(row);

      // Append table to the roomData div
      roomDataDiv.appendChild(table);
    }
  </script>

  
    <!-- Liên kết đến file JavaScript -->
    <script src="admin/js/login-register.js"></script>

    <!-- Thêm một số JavaScript cho modal -->
    <script>
        // Mở modal login
        document.getElementById('openLoginModal').addEventListener('click', function() {
            document.getElementById('loginModal').style.display = 'block';
        });

        // Mở modal register
        document.getElementById('openRegisterModal').addEventListener('click', function() {
            document.getElementById('registerModal').style.display = 'block';
        });

        // Đóng modal login
        document.getElementById('closeLoginModal').addEventListener('click', function() {
            document.getElementById('loginModal').style.display = 'none';
        });

        // Đóng modal register
        document.getElementById('closeRegisterModal').addEventListener('click', function() {
            document.getElementById('registerModal').style.display = 'none';
        });

        // Mở modal logout
        document.getElementById('openLogoutModal').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'block';
        });

        // Đóng modal logout khi nhấn vào dấu "x"
        document.getElementById('closeLogoutModal').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Đóng modal logout khi nhấn vào nút Cancel
        document.getElementById('cancelLogout').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Xác nhận đăng xuất và thực hiện thao tác logout
        document.getElementById('confirmLogout').addEventListener('click', function() {
            alert('You have logged out.');
            document.getElementById('logoutModal').style.display = 'none';
        });
    </script>

</body>

</html>
