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
  images: [{ 
    type: String, // Lưu đường dẫn URL ảnh
    trim: true,
  }], 
  facilities: [{ 
    type: String, // Mảng tiện nghi (Wi-Fi, máy lạnh, v.v.)
    enum: ['Wi-Fi', 'Máy lạnh', 'Bồn tắm vòi sen', 'TV thông Minh', 'Máy sấy', 'Bàn làm việc', 'Bếp', 'TV thông Minh', 'Hệ thống điều khiển thông minh', 'Đồ vệ sinh cá nhân cao cấp', 'Két an toàn'], // Các tiện nghi có sẵn
    trim: true 
  }],
  features: [{ 
    type: String, // Các tính năng đặc biệt của phòng
    enum: ['View đẹp', 'Ban công', 'Bể bơi riêng', 'Jacuzzi', 'Vệ sinh miễn phí', 'Giường lớn', '1 giường', '2 giường', '1 phòng', '2 phòng', '3 phòng', '2 giường', '3 giường', 'Giường lớn'], 
    trim: true 
  }],
  guests: { 
    type: Number, // Số lượng khách tối đa mà phòng có thể chứa
    required: true, 
    min: [1, 'Số lượng khách không thể nhỏ hơn 1'],
    max: [10, 'Số lượng khách không thể lớn hơn 10'] 
  },
  rating: { 
    type: Number, // Đánh giá của phòng, từ 1 đến 5
    required: true, 
    min: [1, 'Đánh giá phải từ 1 đến 5'], 
    max: [5, 'Đánh giá phải từ 1 đến 5']
  },
bookings: [{
  startDate: { type: Date, required: true }, // Ngày bắt đầu của booking
  endDate: { type: Date, required: true },   // Ngày kết thúc của booking
  guestCount: { type: Number, required: true } // Số lượng khách
}]
}, { timestamps: true });

// Thuộc tính ảo để tính số lượng phòng đã đặt
RoomSchema.virtual('bookedQuantity').get(function() {
  return this.bookings.reduce((acc, booking) => {
    return acc + booking.guestCount;
  }, 0);
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

// Phương thức để lấy số lượng phòng từ tính năng "features"
RoomSchema.methods.getNumberOfRooms = function() {
  const roomFeature = this.features.find(feature => feature.includes("phòng"));
  if (roomFeature) {
    const numberOfRooms = parseInt(roomFeature.replace(/[^\d]/g, ''));
    return numberOfRooms;
  }
  return 0;  // Trả về 0 nếu không tìm thấy thông tin số phòng
};

// Xuất mô hình Room dưới dạng ES Module
const Room = mongoose.model('Room', RoomSchema);
export default Room;
