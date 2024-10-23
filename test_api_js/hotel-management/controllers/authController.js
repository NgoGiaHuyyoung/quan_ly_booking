const User = require('../models/User');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const crypto = require('crypto'); // Thêm để tạo token
const nodemailer = require('nodemailer'); // Thêm để gửi email

// Cấu hình gmail
const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS,
  },
});

// Hàm gửi email
const sendEmail = async (to, subject, text) => {
  const mailOptions = {
    from: process.env.EMAIL_USER,
    to,
    subject,
    text,
  };

  return new Promise((resolve, reject) => {
    transporter.sendMail(mailOptions, (error, info) => {
      if (error) {
        console.error('Error sending email:', error);
        return reject(error);
      }
      console.log('Email sent:', info.response);
      resolve(info);
    });
  });
};

// Đăng ký người dùng
exports.register = async (req, res) => {
  const { name, username, password, phone, email, role } = req.body;

  if (!name || !username || !password || !phone || !email || !role) {
    return res.status(400).json({ message: 'Vui lòng cung cấp tất cả các trường bắt buộc.' });
  }

  try {
    const hashedPassword = await bcrypt.hash(password, 10);
    const user = new User({ name, username, password: hashedPassword, phone, email, role });

    await user.save();
    res.status(201).json({ message: 'User registered successfully' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Đăng nhập người dùng
exports.login = async (req, res) => {
  const { username, password } = req.body;

  if (!username || !password) {
    return res.status(400).json({ message: 'Vui lòng cung cấp tên người dùng và mật khẩu.' });
  }

  try {
    const user = await User.findOne({ username });
    if (!user || !(await bcrypt.compare(password, user.password))) {
      return res.status(401).json({ message: 'Invalid credentials' });
    }

    const token = jwt.sign({ id: user._id, role: user.role }, process.env.JWT_SECRET, { expiresIn: '1h' });
    res.json({ token });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Quên mật khẩu
exports.forgotPassword = async (req, res) => {
  const { email } = req.body;

  try {
    const user = await User.findOne({ email });
    if (!user) {
      return res.status(404).json({ message: 'Không tìm thấy người dùng với email này.' });
    }

    // Tạo token reset mật khẩu
    const token = crypto.randomBytes(20).toString('hex');

    // Thiết lập token và thời gian hết hạn
    user.resetPasswordToken = token;
    user.resetPasswordExpires = Date.now() + 3600000; // Token có hạn trong 1 giờ

    await user.save();

    // Gửi email chứa liên kết đặt lại mật khẩu
    await sendEmail(
      user.email,
      'Đặt lại mật khẩu',
      `Bạn đang nhận được email này vì bạn (hoặc ai đó) đã yêu cầu đặt lại mật khẩu cho tài khoản của bạn.\n\n` +
      `Vui lòng nhấp vào liên kết sau để đặt lại mật khẩu:\n\n` +
      `http://${req.headers.host}/reset/${token}\n\n` +
      `Nếu bạn không yêu cầu, vui lòng bỏ qua email này và mật khẩu của bạn sẽ không bị thay đổi.\n`
    );

    res.status(200).json({ message: 'Email đặt lại mật khẩu đã được gửi đến ' + user.email });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Khôi phục mật khẩu
exports.resetPassword = async (req, res) => {
  const { token } = req.params;
  console.log("Received token:", token); // Log token
  const { newPassword } = req.body;

  try {
    const user = await User.findOne({
      resetPasswordToken: token,
      resetPasswordExpires: { $gt: Date.now() }
    });

    if (!user) {
      return res.status(400).json({ message: 'Token không hợp lệ hoặc đã hết hạn.' });
    }

    user.password = await bcrypt.hash(newPassword, 10);
    user.resetPasswordToken = undefined;
    user.resetPasswordExpires = undefined;

    await user.save();
    res.status(200).json({ message: 'Mật khẩu đã được cập nhật thành công.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Thay đổi mật khẩu
exports.changePassword = async (req, res) => {
  const { oldPassword, newPassword } = req.body;
  const userId = req.user.id; // Lấy ID người dùng từ token JWT

  try {
    const user = await User.findById(userId);
    if (!user || !(await bcrypt.compare(oldPassword, user.password))) {
      return res.status(401).json({ message: 'Mật khẩu cũ không chính xác.' });
    }

    // Hash mật khẩu mới và lưu vào người dùng
    user.password = await bcrypt.hash(newPassword, 10);
    await user.save();

    res.status(200).json({ message: 'Mật khẩu đã được cập nhật thành công.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
