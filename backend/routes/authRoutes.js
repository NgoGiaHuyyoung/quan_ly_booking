import express from 'express';
import {
  register,
  verifyEmail,
  login,
  adminLogin,
  refreshToken,
  forgotPassword,
  resetPassword,
  changePassword,
  logout,
  createAdmin
} from '../controllers/authController.js';
import { verifyToken, verifyRole } from '../middlewares/auth.js';
import { validateRegistration, validateLogin, validate } from '../middlewares/express-validator.js';
import { body } from 'express-validator';
import jwt from 'jsonwebtoken';


const router = express.Router();


router.post('/logout',verifyToken,logout)
router.post('/register', validateRegistration, validate, register); 
router.post('/create-admin', createAdmin,verifyRole); 
router.post('/verifyEmail', verifyEmail);
// router.post('/login', validateLogin, validate, login); 
router.post('/login',login);
router.post('/admin-login', verifyRole(['admin']),adminLogin);
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


// router.get('/admin', verifyToken, verifyRole(['admin']), adminDashboard);
// router.get('/staff', verifyToken, verifyRole(['staff', 'admin']), staffDashboard);
// router.get('/user', verifyToken, verifyRole(['user', 'admin']), userDashboard);
// router.get('/profile', verifyToken, verifyRole(['customer', 'admin']), getProfile);
// router.get('/admin-dashboard', verifyToken, verifyRole(['admin']), getAdminDashboard);


export default router;
