import mongoose from 'mongoose';

const LogSchema = new mongoose.Schema({
  action: { type: String, required: true },
  user: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true },
  timestamp: { type: Date, default: Date.now },
  details: { type: Object, required: true }, // Chi tiết hành động
  ipAddress: { type: String },   // Địa chỉ IP
  userAgent: { type: String },   // Thông tin user-agent
});

export default mongoose.model('Log', LogSchema);  // Đảm bảo sử dụng export default
