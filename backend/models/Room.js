import mongoose from 'mongoose';

const RoomSchema = new mongoose.Schema({
  name: { 
    type: String, 
    required: true, 
    trim: true 
  },
  type: { 
    type: String, 
    required: true, 
    trim: true 
  },
  price: { 
    type: Number, 
    required: true, 
    min: [0, 'Giá không thể âm'] 
  },
  status: { 
    type: String, 
    enum: ['available', 'booked', 'in_use', 'under_maintenance', 'cleaning', 'cleaned'], 
    default: 'available' 
  },
  quantity: { 
    type: Number, 
    required: true, 
    default: 1,
    min: [0, 'Số lượng phòng không thể âm']
  }, 
  availableQuantity: { 
    type: Number, 
    required: true, 
    default: 1,
    min: [0, 'Số phòng có sẵn không thể âm']
  },
}, { timestamps: true });

// Thuộc tính ảo để tính số lượng phòng đã đặt
RoomSchema.virtual('bookedQuantity').get(function() {
  return this.quantity - this.availableQuantity;
});

// Middleware để đảm bảo availableQuantity không vượt quá quantity
RoomSchema.pre('save', function(next) {
  if (this.availableQuantity > this.quantity) {
    this.availableQuantity = this.quantity;
  }
  next();
});

// Middleware để cập nhật availableQuantity nếu quantity thay đổi
RoomSchema.pre('findOneAndUpdate', function(next) {
  const update = this.getUpdate();
  if (update.quantity && update.availableQuantity > update.quantity) {
    update.availableQuantity = update.quantity;
  }
  next();
});

// Xuất mô hình Room dưới dạng ES Module
const Room = mongoose.model('Room', RoomSchema);
export default Room;
