<form id="searchForm">
        <div class="row align-items-end">
          <!-- Name -->
          <div class="col-lg-3 mb-3">
            <label class="form-label" style="font-weight: 500;">Name</label>
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