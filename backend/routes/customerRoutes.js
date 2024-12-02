// customerRoutes.js

import express from 'express';
import { getAllCustomers, getCustomerById, createCustomer, updateCustomer, deleteCustomer } from '../controllers/customerController.js';
import { verifyToken } from '../middlewares/auth.js'; // Middleware xác thực nếu cần

const router = express.Router();

// Định nghĩa các route với các hàm callback hợp lệ
router.get('/', verifyToken, getAllCustomers);
router.get('/:id', verifyToken, getCustomerById);
router.post('/', verifyToken, createCustomer);
router.put('/:id', verifyToken, updateCustomer);
router.delete('/:id', verifyToken, deleteCustomer);

export default router;
