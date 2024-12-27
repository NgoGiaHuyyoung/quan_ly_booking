<div class="container availability-form">
  <div class="row">
    <div class="col-lg-12 bg-white shadow p-4 rounded">
      <h5 class="mb-4">Check Booking Availability</h5>
      <form id="searchForm">
        <div class="row align-items-end">
          <!-- Name -->
          <div class="col-lg-3 mb-3">
            <label class="form-label" style="font-weight: 500;">Name</label>
            <input type="text" id="name" class="form-control shadow-none" required>
          </div>
          <!-- Check-in -->
          <div class="col-lg-3 mb-3">
            <label class="form-label" style="font-weight: 500;">Check-in</label>
            <input type="date" id="startDate" class="form-control shadow-none" required>
          </div>
          <!-- Check-out -->
          <div class="col-lg-3 mb-3">
            <label class="form-label" style="font-weight: 500;">Check-out</label>
            <input type="date" id="endDate" class="form-control shadow-none" required>
          </div>
          <!-- Number of Guests (Adults) -->
          <div class="col-lg-2 mb-3">
            <label class="form-label" style="font-weight: 500;">Adult</label>
            <select id="guests" class="form-select shadow-none" required>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
            </select>
          </div>
          <!-- Number of Rooms -->
          <div class="col-lg-2 mb-3">
            <label class="form-label" style="font-weight: 500;">Number of Rooms</label>
            <select id="numberOfRooms" class="form-select shadow-none" required>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
              <option value="4">Four</option>
              <option value="5">Five</option>
            </select>
          </div>
          <!-- Search Button -->
          <div class="col-lg-1 mb-lg-3 mt-2">
            <button type="submit" class="btn text-white shadow-none custom-bg">Search</button>
          </div>
        </div>
      </form>

      <!-- Container to display search results -->
      <div id="searchResults" class="room-results mt-4">
        <!-- Results will be displayed here -->
      </div>
    </div>
  </div>
</div>

<script>
  // Hàm tìm kiếm phòng
  document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Ngăn không cho form submit theo cách mặc định

    // Lấy dữ liệu từ form
    const name = document.getElementById('name').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const guests = document.getElementById('guests').value;
    const numberOfRooms = document.getElementById('numberOfRooms').value;

    // Kiểm tra xem tất cả các trường đã được nhập hay chưa
    if (!name || !startDate || !endDate || !guests || !numberOfRooms) {
      alert("Please fill all the fields.");
      return;
    }

    // Lấy token từ sessionStorage
    const token = sessionStorage.getItem('accessToken');  // Lấy token từ sessionStorage

    if (!token) {
      alert("You must be logged in to search.");
      return;
    }

    // Cấu trúc đối tượng dữ liệu cần gửi (theo cấu trúc searchRoom trong API)
    const requestData = {
      name: name,                // Tên người dùng
      startDate: startDate,      // Ngày check-in
      endDate: endDate,          // Ngày check-out
      guests: parseInt(guests),  // Số lượng khách (tính cả người lớn)
      numberOfRooms: parseInt(numberOfRooms),  // Số phòng yêu cầu
    };

    // Gửi yêu cầu API bằng fetch
    fetch('http://localhost:5000/api/rooms/search', {  // Thay đổi URL cho đúng với endpoint của bạn
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token  // Thêm token vào header
      },
      body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
      if (data.length > 0) {
        displayResults(data);  // Hiển thị kết quả tìm kiếm
      } else {
        alert("No rooms available for the selected dates.");
      }
    })
    .catch(error => {
      console.error("Error fetching rooms:", error);
      alert("An error occurred while searching.");
    });
  });

  // Hàm hiển thị kết quả tìm kiếm
  function displayResults(rooms) {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '';  // Xóa kết quả cũ

    rooms.forEach(room => {
      const roomElement = document.createElement('div');
      roomElement.classList.add('room-item', 'mb-3');
      roomElement.innerHTML = `
        <h6>${room.name}</h6>
        <p>Type: ${room.type}</p>
        <p>Price: $${room.price}</p>
        <p>Guests: ${room.guests}</p>
        <p>Status: ${room.status}</p>
        <p>Available Quantity: ${room.availableQuantity}</p>
      `;
      resultsContainer.appendChild(roomElement);
    });
  }
</script>
