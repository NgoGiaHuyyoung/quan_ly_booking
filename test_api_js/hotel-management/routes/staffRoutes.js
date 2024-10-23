const express = require('express');
const { getAllStaff, getStaffById, createStaff, updateStaff, deleteStaff } = require('../controllers/staffController');
const { verifyToken } = require('../middlewares/auth'); // Nếu có dùng middleware

const router = express.Router();

// Định nghĩa các route với callback hợp lệ
router.get('/', verifyToken, getAllStaff); // Lấy danh sách nhân viên
router.get('/:id', verifyToken, getStaffById); // Lấy thông tin nhân viên theo ID
router.post('/', verifyToken, createStaff); // Tạo mới nhân viên
router.put('/:id', verifyToken, updateStaff); // Cập nhật thông tin nhân viên
router.delete('/:id', verifyToken, deleteStaff); // Xóa nhân viên

module.exports = router;
