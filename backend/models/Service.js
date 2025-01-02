import mongoose from 'mongoose';

const serviceSchema = new mongoose.Schema({
  name: {
    type: String,
    required: true,
    trim: true,  // Loại bỏ khoảng trắng thừa
  },
  description: {
    type: String,
    required: true,
  },
  price: {
    type: Number,
    required: true,
    min: [0, 'Giá không thể âm'],  // Đảm bảo giá không âm
  }
}, { 
  timestamps: true  // Tự động tạo trường `createdAt` và `updatedAt`
});

const Service = mongoose.model('Service', serviceSchema);
export default Service;
