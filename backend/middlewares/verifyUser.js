import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';

// Middleware xác thực token
export const verifyToken = (req, res, next) => {
  const authHeader = req.headers['authorization'];
  const token = authHeader && authHeader.split(' ')[1];

  if (!token) {
    return res.status(401).json({ message: 'Không có token, truy cập bị từ chối!' });
  }

  jwt.verify(token, process.env.JWT_SECRET, (err, decoded) => {
    if (err) {
      return res.status(403).json({ message: 'Token không hợp lệ!' });
    }

    req.user = decoded; // Lưu thông tin người dùng vào request
    next();
  });
};

// Middleware kiểm tra người dùng
export const verifyUser = (req, res, next) => {
  if (!req.user || req.user.role !== 'user') {
    return res.status(403).json({ message: 'Bạn không có quyền truy cập!' });
  }
  next();
};

// Pre-save hook để mã hóa mật khẩu người dùng
export const hashPassword = async (user, next) => {
  if (user.isModified('password')) {
    if (user.password.length < 10 || user.password.length > 20) {
      return next(new Error('Password must be between 10 and 20 characters.'));
    }

    try {
      const salt = await bcrypt.genSalt(10);
      user.password = await bcrypt.hash(user.password, salt);
      next();
    } catch (error) {
      return next(error);
    }
  } else {
    next();
  }
};
