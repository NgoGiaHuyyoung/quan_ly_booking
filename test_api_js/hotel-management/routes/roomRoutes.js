const express = require('express');
const { getAllRooms, getRoomById, createRoom, updateRoom, deleteRoom } = require('../controllers/roomController');
const { verifyToken } = require('../middlewares/auth'); // Nếu bạn có middleware xác thực

const router = express.Router();

// Định nghĩa các route với hàm callback chính xác
router.get('/', verifyToken, getAllRooms); // Sử dụng middleware nếu cần
router.get('/:id', verifyToken, getRoomById);
router.post('/', verifyToken, createRoom);
router.put('/:id', verifyToken, updateRoom);
router.delete('/:id', verifyToken, deleteRoom);

module.exports = router;
