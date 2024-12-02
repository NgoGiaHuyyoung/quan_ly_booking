import express from 'express';
import { getAllBills, getBillById, createBill, updateBill, deleteBill, refundBill } from '../controllers/billingController.js';
import { verifyToken } from '../middlewares/auth.js';

const router = express.Router();

router.get('/', verifyToken, getAllBills); 
router.get('/:id', verifyToken, getBillById);
router.post('/', verifyToken, createBill);
router.put('/:id', verifyToken, updateBill); 
router.delete('/:id', verifyToken, deleteBill); 
router.post('/refund/:id', verifyToken, refundBill);

export default router;
