// Middleware kiểm tra quyền admin
export const verifyAdmin = (req, res, next) => {
  if (!req.user || req.user.role !== 'admin') {
    return res.status(403).json({ message: 'Chỉ admin mới có quyền truy cập!' });
  }
  next();
};

// Middleware kiểm tra quyền nhân viên
export const verifyStaff = (req, res, next) => {
  if (!req.user || (req.user.role !== 'staff' && req.user.role !== 'admin')) {
    return res.status(403).json({ message: 'Chỉ nhân viên hoặc admin mới có quyền truy cập!' });
  }
  next();
};

// Middleware kiểm tra quyền khách hàng
export const verifyCustomer = (req, res, next) => {
  if (!req.user || req.user.role !== 'customer') {
    return res.status(403).json({ message: 'Chỉ khách hàng mới có quyền truy cập!' });
  }
  next();
};
