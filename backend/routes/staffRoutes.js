import express from 'express';
import { getAllStaff, getStaffById, createStaff, updateStaff, deleteStaff } from '../controllers/staffController.js';
import { verifyToken } from '../middlewares/auth.js'; // Nếu có dùng middleware

const router = express.Router();

// Định nghĩa các route với callback hợp lệ
router.get('/', verifyToken, getAllStaff); // Lấy danh sách nhân viên
router.get('/:id', verifyToken, getStaffById); // Lấy thông tin nhân viên theo ID
router.post('/', verifyToken, createStaff); // Tạo mới nhân viên
router.put('/:id', verifyToken, updateStaff); // Cập nhật thông tin nhân viên
router.delete('/:id', verifyToken, deleteStaff); // Xóa nhân viên

export default router; // Sử dụng export default
