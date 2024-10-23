const express = require('express');
const { getAllCustomers, getCustomerById, createCustomer, updateCustomer, deleteCustomer } = require('../controllers/customerController');
const { verifyToken } = require('../middlewares/auth'); // Middleware xác thực nếu cần

const router = express.Router();

// Định nghĩa các route với các hàm callback hợp lệ
router.get('/', verifyToken, getAllCustomers);
router.get('/:id', verifyToken, getCustomerById);
router.post('/', verifyToken, createCustomer);
router.put('/:id', verifyToken, updateCustomer);
router.delete('/:id', verifyToken, deleteCustomer);

module.exports = router;
