<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">



<nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand me-5 fw-bold fs-3 h-font" href="index.php">Jay Homestay</a>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active me-2" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="rooms.php">Rooms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="facilities.php">Facilities</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2" href="contact.php">Contact us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
      </ul>
      <div class="d-flex">
        <button id="loginLink" type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
          Login
        </button>
        <button id="registerLink" type="button" class="btn btn-outline-dark shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">
          Register
        </button>
        <button id="logoutLink" type="button" class="btn btn-dark shadow-none" data-bs-toggle="modal" data-bs-target="#logoutModal" style="display: none;">
          Logout
        </button>
        <button id="cartLink" href ="cart.php" type="button" class="btn btn-outline-dark shadow-none me-lg-3" data-bs-toggle="modal" data-bs-target="#cartModal" onclick="window.location.href='cart.php'">
          <i class="fas fa-shopping-cart"></i> Cart
          <span class="badge bg-danger rounded-pill"></span>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Modal Login-->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="loginForm">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-circle fs-3 me-2"></i>User Login
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" id="loginEmail" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" id="loginPassword" required>
          </div>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <button type="submit" class="btn btn-dark">LOGIN</button>
            <a href="javascript: void(0)" class="text-secondary text-decoration-none">Forgot Password?</a>
          </div>
          <div id="loginError" class="text-danger mt-2" style="display: none;">Invalid email or password.</div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Register -->
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="registerForm">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-lines-fill fs-3 me-2"></i>User Registration
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" id="registerName" placeholder="Enter your full name" required>
              </div>
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Age</label>
                <input type="number" class="form-control" id="registerAge" placeholder="Enter your age" required>
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Gender</label>
                <select class="form-control" id="registerGender" required>
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="registerUsername" placeholder="Choose a username" required>
              </div>
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" id="registerEmail" placeholder="Enter your email" required>
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Phone Number</label>
                <input type="number" class="form-control" id="registerPhone" placeholder="Enter your phone number" required>
              </div>
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="registerPassword" placeholder="Enter a strong password" required>
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="registerConfirmPassword" placeholder="Confirm your password" required>
              </div>
            </div>
          </div>
          <div class="text-center my-1">
            <button type="submit" class="btn btn-dark">REGISTER</button>
          </div>
          <div id="registerError" class="text-danger mt-2" style="display: none;">Passwords do not match.</div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Welcome</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="userName">Welcome, user!</p>
        <button id="logoutBtn" class="btn btn-dark">Logout</button>
      </div>
    </div>
  </div>
</div>










<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.min.js"></script>