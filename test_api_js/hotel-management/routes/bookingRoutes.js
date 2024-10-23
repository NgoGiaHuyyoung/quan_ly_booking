const express = require('express');
const { getAllBookings, getBookingById, createBooking, updateBooking, deleteBooking } = require('../controllers/bookingController');
const { verifyToken } = require('../middlewares/auth'); // Middleware xác thực

const router = express.Router();

// Định nghĩa các route với hàm callback chính xác
router.get('/', verifyToken, getAllBookings); // Sử dụng middleware nếu cần
router.get('/:id', verifyToken, getBookingById);
router.post('/', verifyToken, createBooking);
router.put('/:id', verifyToken, updateBooking);
router.delete('/:id', verifyToken, deleteBooking);

module.exports = router;
