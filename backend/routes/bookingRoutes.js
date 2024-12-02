import express from 'express';
import { getAllBookings, getBookingById, createBooking, updateBooking, deleteBooking, moveRoom } from '../controllers/bookingController.js';
import { verifyToken } from '../middlewares/auth.js';
import { logAction } from '../middlewares/logAction.js'; // Import middleware ghi nhận log

const router = express.Router();

// Ghi nhận log khi lấy tất cả bookings
router.get('/', verifyToken, logAction('GET_ALL_BOOKINGS'), getAllBookings);

// Ghi nhận log khi lấy booking theo ID
router.get('/:id', verifyToken, logAction('GET_BOOKING_BY_ID'), getBookingById);

// Ghi nhận log khi tạo booking mới
router.post('/', verifyToken, logAction('CREATE_BOOKING'), createBooking);

// Ghi nhận log khi cập nhật thông tin booking
router.put('/:id', verifyToken, logAction('UPDATE_BOOKING'), updateBooking);

// Ghi nhận log khi xóa booking
router.delete('/:id', verifyToken, logAction('DELETE_BOOKING'), deleteBooking);

// Ghi nhận log khi chuyển phòng
router.post('/move', verifyToken, logAction('MOVE_ROOM'), moveRoom);

export default router;
