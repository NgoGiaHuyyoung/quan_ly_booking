import { createLog } from '../controllers/logController.js'; // Import controller để tạo log

// Middleware ghi nhận log hành động
export const logAction = (action) => {
  return async (req, res, next) => {
    try {
      // Lấy user từ middleware xác thực (được lấy từ middleware auth.js)
      const userId = req.user ? req.user._id : null; 

      // Lấy thông tin chi tiết hành động (body, params, query)
      const details = { body: req.body, params: req.params, query: req.query };

      // Lấy địa chỉ IP và user-agent từ header
      const ipAddress = req.ip;
      const userAgent = req.headers['user-agent'];

      // Tạo log mới trong hệ thống
      await createLog(action, userId, details, ipAddress, userAgent);

      // Tiếp tục với hành động tiếp theo
      next();
    } catch (error) {
      console.error('Error logging action:', error);
      next(error); // Chuyển lỗi cho middleware tiếp theo
    }
  };
};
