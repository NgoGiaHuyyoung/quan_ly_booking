import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';
import crypto from 'crypto';
import nodemailer from 'nodemailer';
import User from '../models/User.js';
import logger from '../utils/logger.js';
import VerificationCode from '../models/VerificationCode.js';

// Cấu hình Gmail
const transporter = (() => {
  try {
    return nodemailer.createTransport({
      service: 'gmail',
      auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASS,
      },
    });
  } catch (error) {
    console.error('Error configuring email transporter:', error);
    throw new Error('Email service is unavailable.');
  }
})();

// Hàm gửi email
const sendEmail = async (to, subject, text) => {
  const mailOptions = {
    from: process.env.EMAIL_USER,
    to,
    subject,
    text,
  };

  try {
    await transporter.sendMail(mailOptions);
    console.log(`Email sent to ${to}`);
  } catch (error) {
    console.error(`Error sending email to ${to}:`, error);
    throw error;
  }
};

// Hàm tạo access token và refresh token
const generateTokens = (user) => {
  const accessToken = jwt.sign({ id: user._id, role: user.role }, process.env.JWT_SECRET, { expiresIn: '1h' });
  const refreshToken = jwt.sign({ id: user._id }, process.env.JWT_REFRESH_SECRET, { expiresIn: '7d' });
  return { accessToken, refreshToken };
};

// Đăng ký tài khoản mới
export const register = async (req, res) => {
  const { name, username, email, password, phone, role = 'customer' } = req.body;

  // Regex kiểm tra mật khẩu phải có ít nhất một ký tự viết hoa, một ký tự viết thường, một số và một ký tự đặc biệt
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;
  if (!passwordRegex.test(password)) {
    return res.status(400).json({
      success: false,
      message: 'Password must meet complexity requirements.',
    });
  }

  try {
    const existingUser = await User.findOne({ email });
    if (existingUser) {
      return res.status(409).json({
        success: false,
        message: 'Email đã được sử dụng.',
      });
    }

    // Hash mật khẩu nếu thỏa mãn yêu cầu
    const hashedPassword = await bcrypt.hash(password, 10);

    // Tạo mã xác thực email
    const emailVerificationCode = crypto.randomBytes(6).toString('hex');  // Tạo mã xác thực ngắn gọn
    const newUser = new User({
      name,
      username,
      email,
      password,
      phone,
      role,
      isEmailVerified: false,
      emailVerificationCode,  // Lưu mã xác thực
      emailVerificationExpires: Date.now() + 3600000, // 1 giờ hết hạn
    });

    await newUser.save();

    // Gửi email với mã xác thực
    const verificationLink = `http://localhost/hbwebsite/frontend/verify-email.php?email=${email}&code=${emailVerificationCode}`;
    await sendEmail(
      email,
      'Xác thực email đăng ký tài khoản',
      `Vui lòng nhấp vào liên kết sau và nhập mã xác thực để kích hoạt tài khoản của bạn: ${verificationLink}`
    );

    res.status(201).json({
      success: true,
      message: 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản.',
    });
  } catch (error) {
    console.error('Error in registration:', error);
    if (error.name === 'ValidationError') {
      return res.status(400).json({
        success: false,
        message: error.message,
      });
    }

    res.status(500).json({
      success: false,
      message: 'Có lỗi xảy ra trong quá trình đăng ký.',
    });
  }
};7

// Xác thực email
export const verifyEmail = async (req, res) => {
  const { email, code } = req.body;

  try {
    // Kiểm tra người dùng với email và mã xác minh
    const user = await User.findOne({
      email: email.trim().toLowerCase(),
      emailVerificationCode: code.trim(),
    });
    if (!user) {
      return res.status(400).json({
        success: false,
        message: 'Mã xác thực không hợp lệ hoặc email không tồn tại.',
      });
    }

    // Kiểm tra thời hạn mã xác thực
    if (user.emailVerificationExpires < Date.now()) {
      return res.status(400).json({
        success: false,
        message: 'Mã xác thực đã hết hạn. Vui lòng yêu cầu mã mới.',
      });
    }

    // Cập nhật trạng thái đã xác minh
    user.isEmailVerified = true;
    user.emailVerificationCode = undefined;
    user.emailVerificationExpires = undefined;

    await user.save();

    return res.status(200).json({
      success: true,
      message: 'Email xác thực thành công.',
    });
  } catch (err) {
    console.error(err);
    return res.status(500).json({
      success: false,
      message: 'Có lỗi xảy ra trong quá trình xác thực email.',
    });
  }
};



// Đăng nhập người dùng
export const login = async (req, res) => {
  const { email, password } = req.body;

  if (!email || !password) {
    return res.status(400).json({ success: false, message: 'Vui lòng cung cấp email và mật khẩu.' });
  }

  try {
    const user = await User.findOne({ email });
    if (!user) {
      return res.status(401).json({ success: false, message: 'Thông tin đăng nhập không chính xác.' });
    }

    if (!user.isEmailVerified) {
      return res.status(401).json({ success: false, message: 'Tài khoản chưa được xác minh qua email.' });
    }

    const passwordMatch = await bcrypt.compare(password, user.password);
    if (!passwordMatch) {
      return res.status(401).json({ success: false, message: 'Thông tin đăng nhập không chính xác.' });
    }

    const { accessToken, refreshToken } = generateTokens(user);
    res.json({ success: true, accessToken, refreshToken });
  } catch (error) {
    console.error('Error during login:', error);
    res.status(500).json({ success: false, message: 'Có lỗi xảy ra!' });
  }
};

// Quên mật khẩu
export const forgotPassword = async (req, res) => {
  const { email } = req.body;

  try {
    const user = await User.findOne({ email });
    if (!user) return res.status(404).json({ message: 'Không tìm thấy người dùng với email này.' });

    const token = crypto.randomBytes(20).toString('hex');
    user.resetPasswordToken = token;
    user.resetPasswordExpires = Date.now() + 3600000;

    await user.save();
    await sendEmail(
      user.email,
      'Đặt lại mật khẩu',
      `Vui lòng nhấp vào liên kết sau để đặt lại mật khẩu:\n\nhttp://${req.headers.host}/reset/${token}`
    );

    res.status(200).json({ message: `Email đặt lại mật khẩu đã được gửi đến ${user.email}` });
  } catch (error) {
    console.error('Error in forgot password:', error);
    res.status(500).json({ message: 'Có lỗi xảy ra!' });
  }
};

// Khôi phục mật khẩu
export const resetPassword = async (req, res) => {
  const { token } = req.params;
  const { newPassword } = req.body;

  try {
    const user = await User.findOne({
      resetPasswordToken: token,
      resetPasswordExpires: { $gt: Date.now() },
    });

    if (!user) return res.status(400).json({ message: 'Token không hợp lệ hoặc đã hết hạn.' });

    user.password = await bcrypt.hash(newPassword, 10);
    user.resetPasswordToken = undefined;
    user.resetPasswordExpires = undefined;

    await user.save();
    res.status(200).json({ message: 'Mật khẩu đã được cập nhật thành công.' });
  } catch (error) {
    console.error('Error in reset password:', error);
    res.status(500).json({ message: 'Có lỗi xảy ra!' });
  }
};

// Thay đổi mật khẩu
export const changePassword = async (req, res) => {
  const { oldPassword, newPassword } = req.body;
  const userId = req.user.id;

  try {
    const user = await User.findById(userId);
    if (!user || !(await bcrypt.compare(oldPassword, user.password))) {
      return res.status(401).json({ message: 'Mật khẩu cũ không chính xác.' });
    }

    user.password = await bcrypt.hash(newPassword, 10);
    await user.save();

    res.status(200).json({ message: 'Mật khẩu đã được cập nhật thành công.' });
  } catch (error) {
    console.error('Error in change password:', error);
    res.status(500).json({ message: 'Có lỗi xảy ra!' });
  }
};


// Refresh token
export const refreshToken = async (req, res) => {
  const { refreshToken } = req.body;

  if (!refreshToken) {
    return res.status(400).json({ message: 'Refresh token không được để trống.' });
  }

  try {
    jwt.verify(refreshToken, process.env.JWT_REFRESH_SECRET, (err, decoded) => {
      if (err) {
        return res.status(403).json({ message: 'Refresh token không hợp lệ.' });
      }

      const newAccessToken = jwt.sign(
        { id: decoded.id, role: decoded.role },
        process.env.JWT_SECRET,
        { expiresIn: '1h' }
      );
      res.json({ accessToken: newAccessToken });
    });
  } catch (error) {
    console.error('Error in refresh token:', error);
    res.status(500).json({ message: 'Có lỗi xảy ra!' });
  }
};



// Lấy danh sách nhân viên
export const getStaff = async (req, res) => {
  try {
    const staff = await User.find({ role: 'staff' });
    if (!staff || staff.length === 0) return res.status(404).json({ message: 'Không có nhân viên nào.' });
    
    res.status(200).json({ staff });
  } catch (error) {
    console.error('Error in getting staff:', error);
    res.status(500).json({ message: 'Có lỗi xảy ra!' });
  }
};

// Xác minh quyền quản trị viên
export const verifyAdmin = async (req, res, next) => {
  try {
    const user = await User.findById(req.user.id);
    if (!user || user.role !== 'admin') {
      return res.status(403).json({ success: false, message: 'Bạn không có quyền truy cập.' });
    }
    next();
  } catch (error) {
    console.error('Error in verifying admin:', error);
    res.status(500).json({ success: false, message: 'Có lỗi xảy ra!' });
  }
};
