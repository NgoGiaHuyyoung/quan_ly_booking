import express from 'express';
import { getAllBills, getBillById, createBill, updateBill, deleteBill, refundBill,handlePaymentCallback } from '../controllers/billingController.js';
import { verifyToken } from '../middlewares/auth.js';

const router = express.Router();

// Route để lấy tất cả các hóa đơn
router.get('/', verifyToken, getAllBills);

// Route để lấy hóa đơn theo ID
router.get('/:id', verifyToken, getBillById);

// Route để tạo hóa đơn mới
router.post('/', verifyToken, createBill);

// Route để cập nhật hóa đơn theo ID
router.put('/:id', verifyToken, updateBill);

// Route để xóa hóa đơn theo ID
router.delete('/:id', verifyToken, deleteBill);

// Route để hoàn tiền cho hóa đơn
router.post('/refund/:id', verifyToken, refundBill);

// Route nhận callback từ MoMo (hoặc hệ thống thanh toán khác) để xử lý kết quả thanh toán
router.post('/payment-callback', handlePaymentCallback);  // Xử lý callback thanh toán

export default router;
