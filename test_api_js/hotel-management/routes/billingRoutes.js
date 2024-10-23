const express = require('express');
const { getAllBills, getBillById, createBill, updateBill, deleteBill } = require('../controllers/billingController');
const { verifyToken } = require('../middlewares/auth'); // nếu có middleware xác thực

const router = express.Router();

// Các route với callback hợp lệ
router.get('/', verifyToken, getAllBills); // Lấy danh sách tất cả các hóa đơn
router.get('/:id', verifyToken, getBillById); // Lấy thông tin hóa đơn theo ID
router.post('/', verifyToken, createBill); // Tạo hóa đơn mới
router.put('/:id', verifyToken, updateBill); // Cập nhật hóa đơn
router.delete('/:id', verifyToken, deleteBill); // Xóa hóa đơn

module.exports = router;
