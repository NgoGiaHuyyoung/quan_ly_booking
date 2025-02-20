<?php
// Kiểm tra nếu có tham số showLoginModal trong URL
$showLoginModal = isset($_GET['showLoginModal']) && $_GET['showLoginModal'] === 'true';
$isLoggedIn = isset($_SESSION['user']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jay Homestay</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <?php require('inc/links.php'); ?>
  <style>
    .availability-form {
      margin-top: -50px;
      z-index: 2;
      position: relative;
    }

    @media screen and (max-width: 575px) {
      .availability-form {
        margin-top: 25px;
        padding: 0 35px;
      }
    }
  </style>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- Carousel -->

  <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
        <img src="images/carousel/1.jpg" class="w-100 d-block" style="width:1920px; height:520px;" />
        </div>
        <div class="swiper-slide">
          <img src="images/carousel/2.png" class="w-100 d-block" style="width:1920px; height:520px;" />
        </div>
        <div class="swiper-slide">
          <img src="images/carousel/3.png" class="w-100 d-block"style="width:1920px; height:520px;" />
        </div>
        <div class="swiper-slide">
          <img src="images/carousel/4.png" class="w-100 d-block" style="width:1920px; height:520px;" />
        </div>
        <div class="swiper-slide">
          <img src="images/carousel/5.png" class="w-100 d-block" style="width:1920px; height:520px;" />
        </div>
        <div class="swiper-slide">
          <img src="images/carousel/6.png" class="w-100 d-block" style="width:1920px; height:520px;" />
        </div>
      </div>
    </div>
  </div>

  <!-- Check availability form -->
  <?php require('inc/Checkavailability.php'); ?>

 
  <?php require('inc/OurRooms.php'); ?>
  <!-- Our Rooms -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font"> OUR ROOMS</h2>

  <div class="container">
    <div class="row">

      <div class="col-lg-4 col-md-6 my-3">
        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
          <img src="images/rooms/1.jpg" class="card-img-top">
          <div class="card-body">
            <h5>Simple Room Name</h5>
            <h6 class="mb-4">$200 per night</h6>
            <div class="features mb-4">
              <h6 class="mb-1">features</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                2 Rooms
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                1 Bathroom
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                1 Balcony
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                3 Sofa
              </span>
            </div>
            <div class="facilities mb-4">
              <h6 class="mb-1">Facilities</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Wifi
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Television
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                AC
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Room heater
              </span>
            </div>
            <div class="guests mb-4">
              <h6 class="mb-1">Guests</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                5 adults
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                4 Children
              </span>
            </div>
            <div class="rating mb-4">
              <h6 class="mb-1">Rating</h6>
              <span class="badge rounded-pill bg-light">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </span>
            </div>
            <div class="d-flex justify-content-evenly mb-2">
              <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Book Now</a>
              <a href="#" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 my-3">
        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
          <img src="images/rooms/1.jpg" class="card-img-top">
          <div class="card-body">
            <h5>Simple Room Name</h5>
            <h6 class="mb-4">$200 per night</h6>
            <div class="features mb-4">
              <h6 class="mb-1">features</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                2 Rooms
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                1 Bathroom
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                1 Balcony
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                3 Sofa
              </span>
            </div>
            <div class="facilities mb-4">
              <h6 class="mb-1">Facilities</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Wifi
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Television
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                AC
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Room heater
              </span>
            </div>
            <div class="guests mb-4">
              <h6 class="mb-1">Guests</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                5 adults
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                4 Children
              </span>
            </div>
            <div class="rating mb-4">
              <h6 class="mb-1">Rating</h6>
              <span class="badge rounded-pill bg-light">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </span>
            </div>
            <div class="d-flex justify-content-evenly mb-2">
              <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Book Now</a>
              <a href="#" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 my-3">
        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
          <img src="images/rooms/1.jpg" class="card-img-top">
          <div class="card-body">
            <h5>Simple Room Name</h5>
            <h6 class="mb-4">$200 per night</h6>
            <div class="features mb-4">
              <h6 class="mb-1">features</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                2 Rooms
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                1 Bathroom
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                1 Balcony
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                3 Sofa
              </span>
            </div>
            <div class="facilities mb-4">
              <h6 class="mb-1">Facilities</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Wifi
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Television
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                AC
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                Room heater
              </span>
            </div>
            <div class="guests mb-4">
              <h6 class="mb-1">Guests</h6>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                5 adults
              </span>
              <span class="badge rounded-pill bg-light text-dark test-wrap">
                4 Children
              </span>
            </div>
            <div class="rating mb-4">
              <h6 class="mb-1">Rating</h6>
              <span class="badge rounded-pill bg-light">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </span>
            </div>
            <div class="d-flex justify-content-evenly mb-2">
              <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Book Now</a>
              <a href="#" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12 text-center mt-5">
        <a href="" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none ">More Rooms>>></a>

      </div>
    </div>
  </div>

  <!-- OUR FACILITIES -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font"> OUR FACILITIES</h2>

  <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/1.svg" width="70px">
        <h5 class="mt-3">Wifi</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/3.svg" width="70px">
        <h5 class="mt-3">Massage</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/4.svg" width="70px">
        <h5 class="mt-3">Fan</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/5.svg" width="70px">
        <h5 class="mt-3">Radio</h5>
      </div>
      <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
        <img src="images/facilities/6.svg" width="70px">
        <h5 class="mt-3">Television</h5>
      </div>
      <div class="col-lg-12 text-center mt-5">
        <a href="" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none ">More Facilities>>></a>

      </div>
    </div>
  </div>

  <!-- TESTIMONIALS -->
  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TESTIMONIALS</h2>

  <div class="container">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <div class="swiper-slide bg-white p-4 ">
          <div class="profile d-flex align-items-center p-4">
            <img src="images/features/star.png" width="30px">
            <h6 class="m-0 ms-2">Random user1</h6>
          </div>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam consectetur accusantium veniam doloremque laborum mollitia nam sequi necessitatibus voluptatum, praesentium nostrum possimus provident placeat soluta eius, iste magnam, dolores accusamus.
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>
        <div class="swiper-slide bg-white p-4 ">
          <div class="profile d-flex align-items-center p-4">
            <img src="images/features/star.png" width="30px">
            <h6 class="m-0 ms-2">Random user1</h6>
          </div>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam consectetur accusantium veniam doloremque laborum mollitia nam sequi necessitatibus voluptatum, praesentium nostrum possimus provident placeat soluta eius, iste magnam, dolores accusamus.
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>
        <div class="swiper-slide bg-white p-4 ">
          <div class="profile d-flex align-items-center p-4">
            <img src="images/features/star.png" width="30px">
            <h6 class="m-0 ms-2">Random user1</h6>
          </div>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam consectetur accusantium veniam doloremque laborum mollitia nam sequi necessitatibus voluptatum, praesentium nostrum possimus provident placeat soluta eius, iste magnam, dolores accusamus.
          </p>
          <div class="rating">
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
      <a href="" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none ">Know More >>></a>
    </div>
  </div>
  <!-- Reach us -->
  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reach us</h2>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <iframe class="w-100 rounded" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.550094511646!2d106.79159707373665!3d10.845701257914802!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527158a0a5b81%3A0xf45c5d34ac580517!2zUGjDom4gaGnhu4d1IFRyxrDhu51uZyDEkOG6oWkgaOG7jWMgR1RWVCB04bqhaSBUcC4gSOG7kyBDaMOtIE1pbmg!5e0!3m2!1svi!2s!4v1728455644576!5m2!1svi!2s" loading="lazy"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
          <h5>Call us</h5>
          <a href="tel: +0938646294" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +0938646294
          </a>
          <br>
          <a href="tel: +0938646294" class="d-inline-block  text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +0938646294
          </a>
        </div>
        <div class="bg-white p-4 rounded mb-4">
          <h5>Follow us us</h5>
          <a href="#" class="d-inline-block mb-3">
            <span class="badge bg-light text-dark fs-6 p-2">
              <i class="bi bi-twitter me-1"></i>Twitter
            </span>
          </a>
          <br>
          <a href="#" class="d-inline-block mb-3">
            <span class="badge bg-light text-dark fs-6 p-2">
              <i class="bi bi-facebook me-1"></i>Facebook
            </span>
          </a>
          <br>
          <a href="#" class="d-inline-block">
            <span class="badge bg-light text-dark fs-6 p-2">
              <i class="bi bi-instagram me-1"></i>Instagram
            </span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <?php require('inc/footer.php') ?>

  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
    });

    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      loop: true,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
  </script>

  <!-- Liên kết đến file JavaScript -->
<script src="admin/js/login-register.js"></script>


<script>
    function addEventListenerSafe(selector, event, callback) {
        const element = document.getElementById(selector);
        if (element) {
            element.addEventListener(event, callback);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Mở modal login
        addEventListenerSafe('openLoginModal', 'click', function() {
            document.getElementById('loginModal').style.display = 'block';
        });

        // Mở modal register
        addEventListenerSafe('openRegisterModal', 'click', function() {
            document.getElementById('registerModal').style.display = 'block';
        });

        // Đóng modal login
        addEventListenerSafe('closeLoginModal', 'click', function() {
            document.getElementById('loginModal').style.display = 'none';
        });

        // Đóng modal register
        addEventListenerSafe('closeRegisterModal', 'click', function() {
            document.getElementById('registerModal').style.display = 'none';
        });

        // Mở modal logout
        addEventListenerSafe('openLogoutModal', 'click', function() {
            document.getElementById('logoutModal').style.display = 'block';
        });

        // Đóng modal logout khi nhấn vào dấu "x"
        addEventListenerSafe('closeLogoutModal', 'click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Đóng modal logout khi nhấn vào nút Cancel
        addEventListenerSafe('cancelLogout', 'click', function() {
            document.getElementById('logoutModal').style.display = 'none';
        });

        // Xác nhận đăng xuất và thực hiện thao tác logout
        addEventListenerSafe('confirmLogout', 'click', function() {
            alert('You have logged out.');
            document.getElementById('logoutModal').style.display = 'none';
        });
    });
    </script>



<!-- <script>
    window.addEventListener('DOMContentLoaded', function () {
      
        const user = sessionStorage.getItem('user');
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
        const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));

        if (user) {
            
            loginModal.hide();
            registerModal.hide();
            logoutModal.show();
        } else {
       
            loginModal.show();
            registerModal.show();
        }
    });
</script> -->

<!-- <script>
  // Lắng nghe sự kiện khi người dùng đăng nhập thành công
  document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();  // Ngừng sự kiện mặc định của form
    
    // Giả sử người dùng đăng nhập thành công, chúng ta sẽ ẩn modal login và register
    $('#loginModal').modal('hide');
    $('#registerModal').modal('hide');
    
    // Hiển thị modal logout
    $('#logoutModal').modal('show');
    
    // Hiển thị alert thông báo login success
    alert("Login success!");

    // Lưu thông tin người dùng vào sessionStorage
    const userData = { username: "exampleUser", email: "example@example.com" }; // Ví dụ thông tin người dùng
    sessionStorage.setItem('user', JSON.stringify(userData));  // Lưu vào sessionStorage
  });

  // Lắng nghe sự kiện khi người dùng đăng ký thành công
  document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();  // Ngừng sự kiện mặc định của form
    
    // Giả sử người dùng đăng ký thành công, chúng ta sẽ ẩn modal login và register
    $('#loginModal').modal('hide');
    $('#registerModal').modal('hide');
    
    // Hiển thị modal logout
    $('#logoutModal').modal('show');
  });

  // Lắng nghe sự kiện khi người dùng nhấn nút logout
  document.getElementById("logoutBtn").addEventListener("click", function() {
    // Xóa thông tin người dùng khỏi sessionStorage
    sessionStorage.removeItem('user');
    
    // Ẩn modal logout
    $('#logoutModal').modal('hide');
    
    // Hiển thị lại modal login và register
    $('#loginModal').modal('show');
    $('#registerModal').modal('show');
  });
</script> -->

</body>

</html>