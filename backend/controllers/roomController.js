import mongoose from 'mongoose';
import Room from '../models/Room.js';
import logger from '../utils/logger.js';
import Booking from '../models/Booking.js'; // Assuming Booking model is imported for moveRoom
import upload from '../utils/upload.js'; // Sử dụng upload từ utils/upload.js
import multer from 'multer';
import path from 'path';
import fs from 'fs';

// Lưu ảnh từ base64
const saveImageFromBase64 = (base64String) => {
  const base64Data = base64String.replace(/^data:image\/\w+;base64,/, "");  // Loại bỏ phần header của base64
  const imageBuffer = Buffer.from(base64Data, 'base64');
  const imagePath = path.join(__dirname, '../uploads', Date.now() + '.png');

  // Lưu tệp vào thư mục uploads
  fs.writeFileSync(imagePath, imageBuffer);
  return imagePath;  // Trả về đường dẫn ảnh đã lưu
};


export const getAllRooms = async (req, res) => {
  try {
    // Lấy tham số phân trang từ query
    const page = parseInt(req.query.page) || 1; // Trang hiện tại (mặc định là trang 1)
    const limit = parseInt(req.query.limit) || 10; // Số phòng mỗi trang (mặc định là 10 phòng)
    const skip = (page - 1) * limit; // Tính toán số lượng phòng cần bỏ qua

    // Đếm phòng theo trạng thái trực tiếp từ MongoDB
    const roomStatusCounts = await Room.aggregate([
      {
        $group: {
          _id: "$status", // Nhóm theo trạng thái
          count: { $sum: 1 }, // Đếm số lượng phòng mỗi trạng thái
        }
      }
    ]);

    // Khởi tạo một đối tượng với các trạng thái có sẵn
    const roomStatusMap = {
      available: 0,
      booked: 0,
      in_use: 0,
      under_maintenance: 0,
      cleaning: 0,
      cleaned: 0,
    };

    // Duyệt qua kết quả đếm và cập nhật roomStatusMap
    roomStatusCounts.forEach((statusCount) => {
      if (roomStatusMap.hasOwnProperty(statusCount._id)) {
        roomStatusMap[statusCount._id] = statusCount.count;
      }
    });

    // Lấy danh sách phòng theo phân trang
    const rooms = await Room.find().skip(skip).limit(limit);

    // Đếm tổng số phòng để tính tổng số trang
    const totalRooms = await Room.countDocuments();
    const totalPages = Math.ceil(totalRooms / limit);

    // Trả về danh sách phòng và thông tin phân trang
    res.status(200).json({
      rooms,
      roomStatusCounts: roomStatusMap,
      totalPages,
      currentPage: page,
    });

    logger.info('Fetched rooms with pagination and status counts');
  } catch (error) {
    logger.error(`Error fetching rooms: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};



// Lấy phòng theo ID
export const getRoomById = async (req, res) => {
  const roomId = req.params.id;

  if (!mongoose.Types.ObjectId.isValid(roomId)) {
    logger.warn(`Invalid room ID: ${roomId}`);
    return res.status(400).json({ message: 'ID phòng không hợp lệ.' });
  }

  try {
    const room = await Room.findById(roomId);
    if (!room) {
      logger.warn(`Room not found with ID: ${roomId}`);
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Fetched room with ID: ${roomId}`);
    res.status(200).json(room);
  } catch (error) {
    logger.error(`Error fetching room: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Tạo phòng mới
export const createRoom = async (req, res) => {
  try {
    // Lấy thông tin từ body của yêu cầu
    const { name, type, price, quantity, availableQuantity, image, facilities, features, guests, rating } = req.body;

    // Kiểm tra và chuyển đổi guests và rating nếu chúng là mảng
    const guestCount = Array.isArray(guests) ? guests[0] : guests; // Nếu là mảng, lấy giá trị đầu tiên
    const roomRating = Array.isArray(rating) ? rating[0] : rating; // Nếu là mảng, lấy giá trị đầu tiên

    // Kiểm tra nếu guests và rating là số
    if (typeof guestCount !== 'number') {
      return res.status(400).json({ message: 'Guests phải là một số' });
    }

    if (typeof roomRating !== 'number') {
      return res.status(400).json({ message: 'Rating phải là một số' });
    }

    // Kiểm tra nếu có ảnh base64
    let imagePath = '';
    if (image) {
      // Xử lý base64 của ảnh
      const base64Data = image.replace(/^data:image\/\w+;base64,/, '');  // Loại bỏ phần đầu của base64
      const buffer = Buffer.from(base64Data, 'base64');
      
      // Tạo tên file ảnh dựa trên thời gian hiện tại
      const fileName = `room-image-${Date.now()}.png`;
      const filePath = path.join('uploads', fileName);

      // Ghi ảnh vào file trên server (giới hạn kích thước ảnh nếu cần)
      await fs.promises.writeFile(filePath, buffer);

      // Lưu đường dẫn ảnh vào cơ sở dữ liệu
      imagePath = `/uploads/${fileName}`;
    }

    // Tạo phòng mới
    const newRoom = new Room({
      name,
      type,
      price, 
      facilities,
      features,
      guests: guestCount,  // Sử dụng số khách đã chuyển đổi
      rating: roomRating,  // Sử dụng đánh giá đã chuyển đổi
      quantity,
      availableQuantity,
      images: imagePath ? [imagePath] : [],  // Lưu đường dẫn ảnh nếu có
      status: 'available',  // Mặc định là phòng có sẵn
    });

    // Lưu phòng mới vào cơ sở dữ liệu
    await newRoom.save();

    // Trả về phòng mới đã tạo
    res.status(201).json(newRoom);
  } catch (error) {
    console.error(error);
    res.status(500).json({ message: error.message });
  }
};


// Cập nhật phòng
export const updateRoom = async (req, res) => {
  const roomId = req.params.id;

  if (!mongoose.Types.ObjectId.isValid(roomId)) {
    logger.warn(`Invalid room ID for update: ${roomId}`);
    return res.status(400).json({ message: 'ID phòng không hợp lệ.' });
  }

  const { quantity, availableQuantity } = req.body;

  if (quantity < availableQuantity) {
    logger.warn('Available quantity exceeds total quantity');
    return res.status(400).json({ message: 'Số phòng có sẵn không thể vượt quá tổng số lượng phòng.' });
  }

  try {
    const updatedRoom = await Room.findByIdAndUpdate(
      roomId,
      req.body,
      { new: true, runValidators: true }
    );
    if (!updatedRoom) {
      logger.warn(`Room not found for update with ID: ${roomId}`);
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Updated room with ID: ${roomId}`);
    res.status(200).json(updatedRoom);
  } catch (error) {
    logger.error(`Error updating room: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Xóa phòng
export const deleteRoom = async (req, res) => {
  const roomId = req.params.id;

  if (!mongoose.Types.ObjectId.isValid(roomId)) {
    logger.warn(`Invalid room ID for deletion: ${roomId}`);
    return res.status(400).json({ message: 'ID phòng không hợp lệ.' });
  }

  try {
    const deletedRoom = await Room.findByIdAndDelete(roomId);
    if (!deletedRoom) {
      logger.warn(`Room not found for deletion with ID: ${roomId}`);
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Deleted room with ID: ${roomId}`);
    res.status(200).json({ message: 'Phòng đã được xóa.' });
  } catch (error) {
    logger.error(`Error deleting room: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật trạng thái phòng
export const updateRoomStatus = async (req, res) => {
  const roomId = req.params.id;
  const { status } = req.body;

  const validStatuses = ['available', 'booked', 'in_use', 'under_maintenance', 'cleaning', 'cleaned'];

  if (!validStatuses.includes(status)) {
    logger.warn(`Invalid status for room ${roomId}: ${status}`);
    return res.status(400).json({ message: 'Trạng thái không hợp lệ.' });
  }

  try {
    const updatedRoom = await Room.findByIdAndUpdate(roomId, { status }, { new: true });
    if (!updatedRoom) {
      logger.warn(`Room not found for status update with ID: ${roomId}`);
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Updated status of room with ID: ${roomId} to ${status}`);
    res.status(200).json(updatedRoom);
  } catch (error) {
    logger.error(`Error updating room status: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Tìm phòng
export const searchRooms = async (req, res) => {
  try {
    const { checkIn, checkOut, name, guests } = req.query;

    // Xây dựng đối tượng truy vấn để tìm kiếm
    let query = {};

    // Tìm theo tên phòng nếu có
    if (name) {
      query.name = { $regex: name, $options: 'i' }; // Tìm kiếm tên phòng không phân biệt chữ hoa/thường
    }

    // Tìm theo số lượng khách nếu có
    if (guests) {
      query.guests = { $gte: parseInt(guests) }; // Tìm phòng có thể chứa ít nhất số lượng khách yêu cầu
    }

    // Kiểm tra ngày đặt phòng (checkIn và checkOut)
    if (checkIn && checkOut) {
      query.bookings = {
        $not: {
          $elemMatch: {
            $or: [
              { checkIn: { $gte: new Date(checkIn), $lt: new Date(checkOut) } },
              { checkOut: { $gt: new Date(checkIn), $lte: new Date(checkOut) } }
            ]
          }
        }
      };
    }

    // Tìm phòng dựa trên truy vấn
    const rooms = await Room.find(query);

    if (rooms.length === 0) {
      return res.status(404).send({ message: 'Không có phòng nào phù hợp.' });
    }

    res.status(200).json(rooms);
  } catch (error) {
    console.error(`Error searching rooms: ${error.message}`);
    res.status(500).send({ message: 'Có lỗi xảy ra khi tìm phòng.' });
  }
};


// Chuyển phòng
export const moveRoom = async (req, res) => {
  const { bookingId, newRoomId } = req.body;

  try {
    const booking = await Booking.findById(bookingId);
    if (!booking) {
      return res.status(404).json({ message: 'Đặt phòng không tồn tại.' });
    }

    const isAvailable = await Booking.isRoomAvailable(newRoomId, booking.startDate, booking.endDate);
    if (!isAvailable) {
      return res.status(400).json({ message: 'Phòng mới không khả dụng.' });
    }

    booking.room = newRoomId;
    await booking.save();

    res.status(200).json({ message: 'Chuyển phòng thành công.', booking });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
