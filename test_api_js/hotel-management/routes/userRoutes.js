const express = require('express');
const { getAllUsers, getUserById, createUser, updateUser, deleteUser } = require('../controllers/userController');
const { verifyToken } = require('../middlewares/auth'); // Nếu bạn có middleware xác thực

const router = express.Router();

// Kiểm tra các route được định nghĩa đúng
router.get('/', verifyToken, getAllUsers); // Sử dụng middleware nếu cần
router.get('/:id', verifyToken, getUserById);
router.post('/', verifyToken, createUser);``    
router.put('/:id', verifyToken, updateUser);
router.delete('/:id', verifyToken, deleteUser);

module.exports = router;
