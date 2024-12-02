import express from 'express';
import { verifyToken, verifyRole } from '../middlewares/auth.js';  // Đảm bảo dùng cú pháp import
import { getAllRooms, getRoomById, createRoom, updateRoom, deleteRoom, updateRoomStatus, searchRooms } from '../controllers/roomController.js'; // Cập nhật để sử dụng các hàm trong controller

const router = express.Router();

// Định nghĩa các route
router.get('/', verifyToken, getAllRooms);
router.get('/:id', verifyToken, getRoomById);
router.post('/', verifyToken, verifyRole(['admin']), createRoom);
router.put('/:id', verifyToken, updateRoom);
router.delete('/:id', verifyToken, verifyRole(['admin']), deleteRoom);
router.put('/status/:id', verifyToken, verifyRole(['admin']), updateRoomStatus);
router.get('/search', verifyToken, searchRooms);

export default router;  // Sử dụng export default để xuất router
