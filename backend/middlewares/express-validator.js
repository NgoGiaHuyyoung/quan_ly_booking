import { body, validationResult } from 'express-validator';
import User from '../models/User.js'; 


const checkUserExists = async (req, res, next) => {
  const { phone, email } = req.body;


  if (email) {
    const existingEmail = await User.findOne({ email });
    if (existingEmail) {
      return res.status(400).json({ message: 'Email này đã tồn tại!' });
    }
  }


  if (phone) {
    const existingPhone = await User.findOne({ phone });
    if (existingPhone) {
      return res.status(400).json({ message: 'Số điện thoại này đã tồn tại!' });
    }
  }

  next();
};


export const validateRegistration = [
  body('phone')
    .isLength({ min: 10, max: 10 })
    .withMessage('Số điện thoại phải có 10 chữ số')
    .matches(/^\d{10}$/)
    .withMessage('Số điện thoại phải chỉ chứa các chữ số'),
  body('name')
    .notEmpty()
    .withMessage('Tên không được để trống'),
  body('email')
    .isEmail()
    .withMessage('Email không hợp lệ'),
  body('password')
    .isLength({ min: 8 })
    .withMessage('Mật khẩu phải có ít nhất 8 ký tự')
    .matches(/[A-Z]/)
    .withMessage('Mật khẩu phải chứa ít nhất một ký tự hoa')
    .matches(/[a-z]/)
    .withMessage('Mật khẩu phải chứa ít nhất một ký tự thường')
    .matches(/\d/)
    .withMessage('Mật khẩu phải chứa ít nhất một chữ số')
    .matches(/[@$!%*?&]/)
    .withMessage('Mật khẩu phải chứa ít nhất một ký tự đặc biệt (@$!%*?&)'),
    // Kiểm tra giới tính
    body('gender')
    .isIn(['male', 'female', 'other'])
    .withMessage('Giới tính phải là male, female hoặc other'),

  // Kiểm tra tuổi
  body('age')
    .isInt({ min: 18 })
    .withMessage('Tuổi phải lớn hơn hoặc bằng 18'),
  checkUserExists, 
];


export const validateLogin = [
  body('email')
    .isEmail()
    .withMessage('Email không hợp lệ'),
  body('password')
    .isLength({ min: 6 })
    .withMessage('Mật khẩu không hợp lệ'),
];


export const validate = (req, res, next) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({ errors: errors.array() });
  }
  next();
};
