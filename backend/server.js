import express from 'express';
import dotenv from 'dotenv';
import cors from 'cors'; // Thêm CORS
import connectDB from './config/db.js'; // Kết nối từ file db.js đã sửa
import authRoutes from './routes/authRoutes.js';
import userRoutes from './routes/userRoutes.js';
import roomRoutes from './routes/roomRoutes.js';
import bookingRoutes from './routes/bookingRoutes.js';
import customerRoutes from './routes/customerRoutes.js';
import staffRoutes from './routes/staffRoutes.js';
import serviceRoutes from './routes/serviceRoutes.js';
import billingRoutes from './routes/billingRoutes.js';
import reviewRoutes from './routes/reviewRoutes.js';
import settingRoutes from './routes/settingRoutes.js';
import logRoutes from './routes/logRoutes.js';
import cartRoutes from './routes/cartRoutes.js';
import voucherRoutes from './routes/voucherRoutes.js';
import path from 'path'; // Thêm module path
import fs from 'fs'; // Thêm module fs
import { fileURLToPath } from 'url'; // Thêm fileURLToPath để lấy __dirname

dotenv.config();

const app = express(); // Khởi tạo express app

// Kết nối đến database
connectDB();

// Middleware CORS
const corsOptions = {
  origin: (origin, callback) => {
    const allowedOrigins = [
      'http://localhost/hbwebsite/frontend', 
      'http://localhost',   
      'http://localhost:5000'
    ]; // Danh sách các origin được phép
    if (!origin || allowedOrigins.includes(origin)) {
      callback(null, true);
    } else {
      callback(new Error('Not allowed by CORS'));
    }
  },
  methods: ['GET', 'POST', 'PUT', 'DELETE'], // Cho phép các phương thức HTTP
  allowedHeaders: ['Content-Type', 'Authorization'], // Các header được phép
  credentials: true, // Cho phép gửi cookie
};
app.use(cors(corsOptions));

// Middleware xử lý JSON và URL-encoded
app.use(express.json({ limit: '50mb' })); // Tăng giới hạn kích thước yêu cầu lên 50MB
app.use(express.urlencoded({ limit: '50mb', extended: true })); // Tăng giới hạn kích thước URL encoded

// Lấy __dirname trong ES Modules
const __dirname = path.dirname(fileURLToPath(import.meta.url));

// Cấu hình để Express phục vụ ảnh từ thư mục uploads
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Cài đặt routes
app.use('/api/auth', authRoutes);
app.use('/api/users', userRoutes);
app.use('/api/rooms', roomRoutes);
app.use('/api/bookings', bookingRoutes);
app.use('/api/customers', customerRoutes);
app.use('/api/staff', staffRoutes);
app.use('/api/services', serviceRoutes);
app.use('/api/payments', billingRoutes);
app.use('/api/reviews', reviewRoutes);
app.use('/api/settings', settingRoutes);
app.use('/api/logs', logRoutes);
app.use('/api/cart', cartRoutes);
app.use('/api/vouchers', voucherRoutes);

// Middleware xử lý lỗi khi không tìm thấy route
app.use((req, res, next) => {
  const error = new Error('Not Found');
  error.status = 404;
  next(error);
});

// Centralized error handler
app.use((error, req, res, next) => {
  const statusCode = error.status || 500;
  const message = error.message || 'Server Error';
  res.status(statusCode).json({ message });
});

// Khởi động server
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
