import mongoose from 'mongoose';
import dotenv from 'dotenv';

dotenv.config(); // Đọc các biến môi trường từ .env file

const connectDB = async () => {
  try {
    await mongoose.connect(process.env.MONGO_URI, {
      // Loại bỏ các tùy chọn deprecated
    });
    console.log('MongoDB connected');
  } catch (error) {
    console.error('Error connecting to MongoDB:', error.message);
    process.exit(1); // Dừng ứng dụng nếu kết nối thất bại
  }
};

export default connectDB;
