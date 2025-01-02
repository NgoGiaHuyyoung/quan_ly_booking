import express from 'express';
import { createVoucher, checkVoucher, toggleVoucherStatus, applyVoucher } from '../controllers/voucherController.js';

const router = express.Router();

router.post('/apply', applyVoucher);

// API để tạo voucher mới
router.post('/create', createVoucher);

// API để kiểm tra voucher
router.post('/check', checkVoucher);

// API để kích hoạt hoặc vô hiệu hóa voucher
router.patch('/status', toggleVoucherStatus);

export default router;
