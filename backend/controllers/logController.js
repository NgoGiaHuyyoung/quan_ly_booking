import Log from '../models/Log.js';
import logger from '../utils/logger.js'; // Import logger

// Lấy danh sách logs với phân trang
export const getAllLogs = async (req, res) => {
  try {
    const { page = 1, limit = 10 } = req.query; // Phân trang
    const logs = await Log.find()
      .populate('user', 'name email') // Hiển thị thông tin user
      .sort({ timestamp: -1 }) // Sắp xếp theo thời gian mới nhất
      .skip((page - 1) * limit)
      .limit(Number(limit));
    const total = await Log.countDocuments();
    res.status(200).json({ total, page, limit, logs });
  } catch (error) {
    logger.error(`Error fetching logs: ${error.message}`);
    res.status(500).json({ message: 'Error fetching logs', error });
  }
};

// Hàm tạo log mới (sửa để nhận thông tin từ req)
export const createLog = async (action, userId, details, req) => {
  try {
    const log = new Log({
      action,
      user: userId,
      details,
      ipAddress: req.ip,      // Lấy địa chỉ IP từ request
      userAgent: req.get('User-Agent'),  // Lấy user-agent từ header
    });

    await log.save(); // Lưu log vào MongoDB
    logger.info(`Log created: ${action} for user ${userId}`);
  } catch (error) {
    logger.error(`Error creating log: ${error.message}`);
    throw error;
  }
};

// Xóa log cũ (ví dụ: log hơn 6 tháng)
export const deleteOldLogs = async () => {
  try {
    const sixMonthsAgo = new Date();
    sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6);
    await Log.deleteMany({ timestamp: { $lt: sixMonthsAgo } });
    logger.info('Old logs deleted successfully');
  } catch (error) {
    logger.error(`Error deleting old logs: ${error.message}`);
  }
};
