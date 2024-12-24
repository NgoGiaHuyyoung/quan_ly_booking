import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';
import crypto from 'crypto';
import nodemailer from 'nodemailer';
import User from '../models/User.js';
import logger from '../utils/logger.js';
import VerificationCode from '../models/VerificationCode.js';
import { sendVerificationEmail } from '../services/emailService.js';


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

const generateTokens = (user) => {
 const accessTokenPayload = {
  id: user._id,
  role: user.role,
  email: user.email,  
};
const accessToken = jwt.sign(accessTokenPayload, process.env.JWT_SECRET, { expiresIn: '1h' });
const refreshTokenPayload = { id: user._id };
const refreshToken = jwt.sign(refreshTokenPayload, process.env.JWT_REFRESH_SECRET, { expiresIn: '7d' });
return { accessToken, refreshToken };
};

export const register = async (req, res) => {
  const { name, username, email, password, phone, age, gender } = req.body;

  // Regex kiểm tra độ phức tạp của mật khẩu
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;
  if (!passwordRegex.test(password)) {
    return res.status(400).json({
      success: false,
      message: 'Password must meet complexity requirements.',
    });
  }


  try {
    // Kiểm tra email đã tồn tại hay chưa
    const existingUser = await User.findOne({ email });
    if (existingUser) {
      return res.status(409).json({
        success: false,
        message: 'Email đã được sử dụng.',
      });
    }

    // Tạo token xác thực email
    const verificationToken = crypto.randomBytes(20).toString('hex');

    // Tạo người dùng mới
    const newUser = new User({
      name,
      username,
      email,
      password,
      phone,
      age,
      gender,
      role: 'customer', // Mặc định là khách hàng
      verified: false, // Mặc định chưa xác thực
      verificationToken, // Token xác thực
    });

    // Lưu người dùng vào cơ sở dữ liệu
    await newUser.save();

    // Liên kết xác thực email
    const verificationLink = `http://localhost/hbwebsite/frontend/verify-email.php?email=${email}&token=${verificationToken}`;

    // Gửi email xác thực
    await sendEmail(
      email,
      'Xác thực email đăng ký tài khoản',
      `Vui lòng nhấp vào liên kết sau để kích hoạt tài khoản của bạn: ${verificationLink}`
    );

    res.status(201).json({
      success: true,
      message: 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản.',
    });
  } catch (error) {
    console.error('Error in registration:', error);
    res.status(500).json({
      success: false,
      message: 'Có lỗi xảy ra trong quá trình đăng ký.',
    });
  }
};


export const createAdmin = async (req, res) => {
  try {
    // Kiểm tra xem người dùng đã đăng nhập và có quyền admin hay không
    const userRole = req.user?.role;  // Giả sử bạn đã xác thực người dùng và role lưu trong token

    if (userRole !== 'admin') {
      return res.status(403).json({ success: false, message: 'You do not have permission to create admin' });
    }

    const { username, email, password } = req.body;

    // Kiểm tra xem email hoặc tên đăng nhập đã tồn tại chưa
    const existingUser = await User.findOne({ $or: [{ email }, { username }] });
    if (existingUser) {
      return res.status(400).json({ success: false, message: 'Email or username already exists' });
    }

    // Mã hóa mật khẩu
    const hashedPassword = await bcrypt.hash(password, 10);

    // Tạo tài khoản admin mới với role là 'admin'
    const newAdmin = new User({
      username,
      email,
      password: hashedPassword,
      role: 'admin', // Thiết lập role là 'admin' mặc định
    });

    // Lưu tài khoản admin vào cơ sở dữ liệu
    await newAdmin.save();

    // Trả về phản hồi thành công
    res.status(201).json({ success: true, message: 'Admin created successfully', user: newAdmin });
  } catch (error) {
    console.error(error);
    res.status(500).json({ success: false, message: 'Server error' });
  }
};

export const verifyEmail = async (req, res) => {
  try {
    const { email, token } = req.body;

    // Kiểm tra email và mã xác thực có hợp lệ
    if (!email || !token) {
      return res.status(400).json({
        success: false,
        message: 'Email và mã xác thực là bắt buộc.',
      });
    }

    const cleanedEmail = email.trim().toLowerCase();
    const cleanedToken = token.trim();

    // Tìm người dùng dựa trên email và mã xác thực
    const user = await User.findOne({
      email: cleanedEmail,
      verificationToken: cleanedToken,
    });

    if (!user) {
      return res.status(400).json({
        success: false,
        message: 'Mã xác thực không hợp lệ hoặc email không tồn tại.',
      });
    }

    // Kiểm tra mã xác thực có hết hạn không (nếu có logic cho việc hết hạn)
    // (Nếu có trường `verificationTokenExpires`, bạn cần kiểm tra nó ở đây)
    if (user.verificationTokenExpires && user.verificationTokenExpires < Date.now()) {
      return res.status(400).json({
        success: false,
        message: 'Mã xác thực đã hết hạn. Vui lòng yêu cầu mã mới.',
      });
    }

    // Cập nhật trạng thái xác thực email
    user.verified = true;
    user.verificationToken = null;  // Xóa mã xác thực sau khi đã xác thực
    user.verificationTokenExpires = null;  // Nếu có trường hết hạn, xóa nó đi

    // Lưu thay đổi vào cơ sở dữ liệu
    await user.save();

    // Trả về phản hồi thành công
    res.status(200).json({
      success: true,
      message: 'Email xác thực thành công.',
    });
  } catch (error) {
    console.error('Error verifying email:', error);
    res.status(500).json({
      success: false,
      message: 'Có lỗi xảy ra trong quá trình xác thực email.',
    });
  }
};

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

    if (user.role !== 'customer') {
      return res.status(403).json({ success: false, message: 'Bạn không có quyền truy cập với vai trò này.' });
    }

    if (!user.verified) {
      return res.status(401).json({ success: false, message: 'Tài khoản chưa được xác minh qua email.' });
    }

    // const isMatch = await bcrypt.compare(password, user.password);
    // if (!isMatch) {
    //   return res.status(401).json({ success: false, message: 'Thông tin đăng nhập không chính xác.' });
    // }

    const { accessToken, refreshToken } = generateTokens(user);

    user.accessToken = accessToken;
    user.refreshToken = refreshToken;
    await user.save();

    res.json({
      success: true,
      accessToken,
      refreshToken,
      user: {
        username: user.username,
        email: user.email,
        age: user.age, // Thêm thông tin age
        gender: user.gender, // Thêm thông tin gender
      }
    });
  } catch (error) {
    console.error('Error during login:', error);
    res.status(500).json({ success: false, message: 'Có lỗi xảy ra!' });
  }
};




export const adminLogin = async (req, res) => {
  const { email, password } = req.body;

  // Kiểm tra thông tin đăng nhập
  if (!email || !password) {
    return res.status(400).json({ success: false, message: 'Vui lòng cung cấp email và mật khẩu.' });
  }

  try {
    const user = await User.findOne({ email });
    if (!user) {
      return res.status(401).json({ success: false, message: 'Thông tin đăng nhập không chính xác.' });
    }

    if (!user.verified) {
      return res.status(401).json({ success: false, message: 'Tài khoản chưa được xác minh qua email.' });
    }

    if (password !== user.password) {
      return res.status(401).json({ success: false, message: 'Thông tin đăng nhập không chính xác.' });
    }

  // Kiểm tra nếu role không phải là admin hoặc staff
  if (user.role !== 'admin' && user.role !== 'staff') {
    return res.status(403).json({ success: false, message: 'Không có quyền truy cập.' });
  }

    // Tạo token khi đăng nhập thành công
    const { accessToken, refreshToken } = generateTokens(user);

    // Lưu lại token vào người dùng (nếu cần thiết)
    user.accessToken = accessToken;
    user.refreshToken = refreshToken;
    await user.save();

    // Trả token về client
    res.json({ success: true, accessToken, refreshToken });
  } catch (error) {
    console.error('Error during admin login:', error);
    res.status(500).json({ success: false, message: 'Có lỗi xảy ra!' });
  }
};

export const logout = async (req, res) => {
  const token = req.headers['authorization']?.split(' ')[1]; // Lấy token từ header Authorization

  if (!token) {
    return res.status(401).json({ success: false, message: 'Không có token, truy cập bị từ chối!' });
  }

  // Xác thực token
  jwt.verify(token, process.env.JWT_SECRET, (err, user) => {
    if (err) {
      return res.status(403).json({ success: false, message: 'Token không hợp lệ, vui lòng đăng nhập lại!' });
    }

    // Token hợp lệ, thực hiện logout
    // Xóa token ở phía client (ví dụ: xóa cookie hoặc localStorage)
    res.clearCookie('token');  // Xóa token trong cookie nếu bạn lưu trong cookie
    // Hoặc bạn có thể sử dụng sessionStorage, localStorage nếu token lưu ở đó
    // res.clearCookie('token', { path: '/', httpOnly: true }); nếu cookie lưu với các thuộc tính bảo mật

    return res.status(200).json({ success: true, message: 'Logout thành công!' });
  });
};



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



































// export const login = async (req, res) => {
//   const { email, password } = req.body;

//   if (!email || !password) {
//     return res.status(400).json({ success: false, message: 'Vui lòng cung cấp email và mật khẩu.' });
//   }

//   try {
//     const user = await User.findOne({ email });
//     if (!user) {
//       return res.status(401).json({ success: false, message: 'Thông tin đăng nhập không chính xác.' });
//     }

//     if (!user.verified) {
//       return res.status(401).json({ success: false, message: 'Tài khoản chưa được xác minh qua email.' });
//     }

//     // So sánh mật khẩu người dùng nhập trực tiếp với mật khẩu trong cơ sở dữ liệu
//     if (password !== user.password) {
//       return res.status(401).json({ success: false, message: 'Thông tin đăng nhập không chính xác.' });
//     }

//     const { accessToken, refreshToken } = generateTokens(user);

//     // Cập nhật token trong cơ sở dữ liệu
//     user.accessToken = accessToken;
//     user.refreshToken = refreshToken;
//     await user.save();

//     res.json({ success: true, accessToken, refreshToken });
//   } catch (error) {
//     console.error('Error during login:', error);
//     res.status(500).json({ success: false, message: 'Có lỗi xảy ra!' });
//   }
// };
