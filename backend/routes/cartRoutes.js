import express from 'express';
import {
  getCart,
  addToCart,
  updateCart,
  removeFromCart,
} from '../controllers/cartController.js';
import { verifyToken } from '../middlewares/auth.js';
const router = express.Router();

// Lấy giỏ hàng của khách hàng hiện tại
router.get('/', verifyToken, getCart);

// Thêm sản phẩm vào giỏ hàng
router.post('/add', verifyToken, addToCart);


// Cập nhật giỏ hàng (số lượng sản phẩm)
router.put('/update', verifyToken, updateCart);

// Xóa sản phẩm khỏi giỏ hàng
router.delete('/remove', verifyToken, removeFromCart);

export default router;
