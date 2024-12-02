import jwt from 'jsonwebtoken';
import User from '../models/User.js'; // Đảm bảo rằng bạn đã import model User đúng cách

// Middleware xác thực token
export const verifyToken = (req, res, next) => {
  const authHeader = req.headers['authorization'];
  const token = authHeader && authHeader.split(' ')[1]; // Lấy token từ header Authorization

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

// Middleware xác thực quyền dựa trên vai trò
export const verifyRole = (roles) => async (req, res, next) => {
  try {
    const user = await User.findById(req.user.id);

    if (!user || !roles.includes(user.role)) {
      return res.status(403).json({ message: 'Bạn không có quyền truy cập!' });
    }a

    next();
  } catch (err) {
    return res.status(500).json({ message: 'Lỗi khi xác thực quyền!' });
  }
};
