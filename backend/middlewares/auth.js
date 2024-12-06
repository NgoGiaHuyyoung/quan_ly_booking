import jwt from 'jsonwebtoken';
import User from '../models/User.js';

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

    req.user = decoded; // Đảm bảo req.user được gán thông tin từ JWT
    next();
  });
};

// Middleware kiểm tra quyền hạn
export const verifyRole = (roles) => async (req, res, next) => {
  try {
    if (!req.user || !req.user.id) {
      return res.status(401).json({ message: 'Người dùng không xác thực!' });
    }

    const user = await User.findById(req.user.id);
    if (!user || !roles.includes(user.role)) {
      return res.status(403).json({ message: 'Bạn không có quyền truy cập!' });
    }

    next();
  } catch (err) {
    console.error('Error in verifyRole middleware:', err);
    return res.status(500).json({ message: 'Lỗi khi xác thực quyền!' });
  }
};

