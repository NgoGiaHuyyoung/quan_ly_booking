import express from 'express';
import { getAllBills, getBillById, createBill, updateBill, deleteBill, refundBill, handlePayment,ipnCallback,payment } from '../controllers/billingController.js';
import { verifyToken } from '../middlewares/auth.js';

const router = express.Router();

// Route để lấy tất cả các hóa đơn
router.get('/', verifyToken, getAllBills);

// Route để lấy hóa đơn theo ID
router.get('/:id', verifyToken, getBillById);

// Route để tạo hóa đơn mới
router.post('/bills', createBill);

// Route để xử lý thanh toán (cập nhật trạng thái hóa đơn)
router.post('/payments', handlePayment);

router.post('/payment', payment);


// Route để cập nhật hóa đơn theo ID
router.put('/:id', verifyToken, updateBill);

// Route để xóa hóa đơn theo ID
router.delete('/:id', verifyToken, deleteBill);

// Route để hoàn tiền cho hóa đơn
router.post('/refund/:id', verifyToken, refundBill);

router.post('/ipnCallback', ipnCallback);



export default router;
