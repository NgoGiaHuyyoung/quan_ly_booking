const express = require('express');
const { getAllServices, getServiceById, createService, updateService, deleteService } = require('../controllers/serviceController');
const { verifyToken } = require('../middlewares/auth'); // nếu có middleware

const router = express.Router();

// Các route với callback hợp lệ
router.get('/', verifyToken, getAllServices); // Lấy danh sách tất cả dịch vụ
router.get('/:id', verifyToken, getServiceById); // Lấy thông tin dịch vụ theo ID
router.post('/', verifyToken, createService); // Tạo dịch vụ mới
router.put('/:id', verifyToken, updateService); // Cập nhật dịch vụ
router.delete('/:id', verifyToken, deleteService); // Xóa dịch vụ

module.exports = router;
