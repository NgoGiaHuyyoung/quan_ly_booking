import express from 'express';
import {
  register,
  verifyEmail,
  login,
  refreshToken,
  forgotPassword,
  resetPassword,
  changePassword,
  getStaff,
} from '../controllers/authController.js';
import { verifyToken, verifyRole } from '../middlewares/auth.js';
import { validateRegistration, validateLogin, validate } from '../middlewares/express-validator.js';
import { body } from 'express-validator';

const router = express.Router();


router.post('/register', validateRegistration, validate, register); 
router.post('/verifyEmail', verifyEmail);
router.post('/login', validateLogin, validate, login); 
router.post('/refresh-token', refreshToken); 
router.post(
  '/forgot-password',
  [body('email').isEmail().withMessage('Invalid email format.')],
  validate,
  forgotPassword
); 
router.post(
  '/reset-password/:token',
  [
    body('password')
      .matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/)
      .withMessage('New password must meet security requirements.'),
  ],
  validate,
  resetPassword
); 
router.post('/change-password', verifyToken, validate, changePassword); 
router.get('/staff', verifyToken, verifyRole(['admin']), getStaff);
router.get('/admin-dashboard', verifyToken, verifyRole(['admin']), (req, res) => {
  res.status(200).json({ message: 'Chào mừng admin!' });
});

router.get('/user/:id', verifyToken, (req, res) => {
  if (req.user.role === 'customer') {
    return res.status(403).json({ message: 'Khách hàng không có quyền truy cập vào tài nguyên này.' });
  }
  res.status(200).json(req.user);
});


router.get('/report', verifyToken, verifyRole(['admin', 'staff']), (req, res) => {
  res.status(200).json({ message: 'Truy cập báo cáo thành công!' });
});

export default router;
