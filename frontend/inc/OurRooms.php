<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';

// Lấy roomId từ URL (có thể là chuỗi UUID hoặc số)
$roomId = isset($_GET['id']) && !empty($_GET['id']) ? $_GET['id'] : null;
?>


<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR ROOMS</h2>

<div class="container">
  <div class="row" id="searchResults">
    <!-- Các phòng sẽ được hiển thị ở đây -->
  </div>
</div>

<script>
function displayResults(rooms) {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = ''; // Xóa kết quả cũ

    rooms.forEach(room => {
        // Xử lý đường dẫn ảnh
        const imagePath = room.images[0].startsWith('/uploads/') ?
            `http://localhost:5000${room.images[0]}` :
            room.images[0];

        // Tạo HTML cho từng phòng
        const roomHTML = `
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow">
                <img src="${imagePath}" class="card-img-top" alt="${room.name}">
                <div class="card-body">
                    <h5 class="card-title">${room.name}</h5>
                    <h6 class="mb-3">$${room.price} per night</h6>
                    <div class="features mb-3">
                        <h6 class="mb-1">Features</h6>
                        ${room.features.map(feature => `
                            <span class="badge rounded-pill bg-light text-dark">${feature}</span>
                        `).join('')}
                    </div>
                    <div class="guests mb-3">
                        <h6 class="mb-1">Guests</h6>
                        <span class="badge rounded-pill bg-light text-dark">${room.guests} Adults</span>
                        <span class="badge rounded-pill bg-light text-dark">${room.children} Children</span>
                    </div>
                    <div class="rating mb-3">
                        <h6 class="mb-1">Rating</h6>
                        <span class="badge rounded-pill bg-light">
                            ${'⭐'.repeat(room.rating)}${'☆'.repeat(5 - room.rating)}
                        </span>
                    </div>
                    <div class="d-flex justify-content-evenly">
                        <a href="rooms_details.php?id=${room._id}" class="btn btn-sm text-white custom-bg shadow-none">Book Now</a>
                        <a href="rooms_details.php?id=${room._id}" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                    </div>
                </div>
            </div>
        </div>
        `;

        // Thêm HTML vào container
        resultsContainer.innerHTML += roomHTML;
    });
}

</script>