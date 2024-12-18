<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test Fetch API</title>
</head>

<body>
  <h1>Fetch API Test</h1>
  <button id="fetchBtn">Fetch Data</button>
  <div id="roomData"></div> <!-- Nơi hiển thị danh sách phòng -->
  <pre id="output"></pre>

  <script>
    document.getElementById('fetchBtn').addEventListener('click', () => {
      const output = document.getElementById('output');
      const roomData = document.getElementById('roomData');
      output.textContent = 'Fetching...';
      roomData.innerHTML = ''; // Clear previous data

      // Fetch room data from the server
      fetch('http://localhost:5000/api/rooms', {
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
          output.textContent = 'Data fetched successfully!';
          console.log('Success:', data);
          displayRoomData(data.rooms);
        })
        .catch((error) => {
          output.textContent = `Error: ${error.message}`;
          console.error('Fetch error:', error.message);
        });
    });

// Function to display room data in a table
function displayRoomData(rooms) {
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

  // Table data
  rooms.forEach(room => {
    const row = document.createElement('tr');

    // Ensure correct image URL without duplicate '/uploads'
    const imageHTML = room.images && room.images.length > 0 ? 
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
  });

  // Append table to the roomData div
  roomData.appendChild(table);
}
  </script>
</body>

</html>
