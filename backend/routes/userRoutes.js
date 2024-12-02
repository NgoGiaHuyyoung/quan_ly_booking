import express from 'express';
import { 
  getAllUsers, 
  getUserById, 
  createUser, 
  updateUser, 
  deleteUser, 
  changePassword 
} from '../controllers/userController.js';
import { verifyToken } from '../middlewares/auth.js'; // Middleware xác thực
import { logAction } from '../middlewares/logAction.js'; // Middleware ghi log

const router = express.Router();

// Định nghĩa các route cho người dùng

// Lấy danh sách tất cả người dùng, chỉ admin có thể thực hiện
router.get('/', verifyToken, logAction('GET_ALL_USERS'), getAllUsers);

// Lấy thông tin người dùng theo ID
router.get('/:id', verifyToken, logAction('GET_USER_BY_ID'), getUserById);

// Tạo mới người dùng
router.post('/', verifyToken, logAction('CREATE_USER'), createUser);

// Cập nhật thông tin người dùng
router.put('/:id', verifyToken, logAction('UPDATE_USER'), updateUser);

// Xóa người dùng
router.delete('/:id', verifyToken, logAction('DELETE_USER'), deleteUser);

// Route để thay đổi mật khẩu người dùng
router.put('/change-password', verifyToken, logAction('CHANGE_PASSWORD'), changePassword);

export default router;
