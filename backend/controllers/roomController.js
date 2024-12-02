import mongoose from 'mongoose';
import Room from '../models/Room.js';
import logger from '../utils/logger.js';
import Booking from '../models/Booking.js'; // Assuming Booking model is imported for moveRoom

// Lấy tất cả phòng và phân loại theo trạng thái
export const getAllRooms = async (req, res) => {
  try {
    const rooms = await Room.find();

    const roomStatusCounts = {
      available: 0,
      booked: 0,
      in_use: 0,
      under_maintenance: 0,
      cleaning: 0,
      cleaned: 0,
    };

    rooms.forEach((room) => {
      roomStatusCounts[room.status] = (roomStatusCounts[room.status] || 0) + 1;
    });

    logger.info('Fetched all rooms and their status counts'); // Log action
    res.status(200).json({ rooms, roomStatusCounts });
  } catch (error) {
    logger.error(`Error fetching rooms: ${error.message}`); // Log error
    res.status(500).json({ message: error.message });
  }
};

// Lấy phòng theo ID
export const getRoomById = async (req, res) => {
  const roomId = req.params.id;

  if (!mongoose.Types.ObjectId.isValid(roomId)) {
    logger.warn(`Invalid room ID: ${roomId}`); // Log invalid ID
    return res.status(400).json({ message: 'ID phòng không hợp lệ.' });
  }

  try {
    const room = await Room.findById(roomId);
    if (!room) {
      logger.warn(`Room not found with ID: ${roomId}`); // Log when room is not found
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Fetched room with ID: ${roomId}`); // Log successful fetch
    res.status(200).json(room);
  } catch (error) {
    logger.error(`Error fetching room: ${error.message}`); // Log error
    res.status(500).json({ message: error.message });
  }
};

// Tạo phòng mới
export const createRoom = async (req, res) => {
  const { name, type, price, status, quantity } = req.body;
  try {
    const newRoom = new Room({
      name,
      type,
      price,
      status,
      quantity,
      availableQuantity: quantity,
    });
    await newRoom.save();
    logger.info(`Created new room: ${name} (${type})`); // Log room creation
    res.status(201).json(newRoom);
  } catch (error) {
    logger.error(`Error creating room: ${error.message}`); // Log error
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật phòng
export const updateRoom = async (req, res) => {
  const roomId = req.params.id;

  if (!mongoose.Types.ObjectId.isValid(roomId)) {
    logger.warn(`Invalid room ID for update: ${roomId}`); // Log invalid ID
    return res.status(400).json({ message: 'ID phòng không hợp lệ.' });
  }

  try {
    const updatedRoom = await Room.findByIdAndUpdate(roomId, req.body, { new: true });
    if (!updatedRoom) {
      logger.warn(`Room not found for update with ID: ${roomId}`); // Log when room not found
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Updated room with ID: ${roomId}`); // Log successful update
    res.status(200).json(updatedRoom);
  } catch (error) {
    logger.error(`Error updating room: ${error.message}`); // Log error
    res.status(500).json({ message: error.message });
  }
};

// Xóa phòng
export const deleteRoom = async (req, res) => {
  const roomId = req.params.id;

  if (!mongoose.Types.ObjectId.isValid(roomId)) {
    logger.warn(`Invalid room ID for deletion: ${roomId}`); // Log invalid ID
    return res.status(400).json({ message: 'ID phòng không hợp lệ.' });
  }

  try {
    const deletedRoom = await Room.findByIdAndDelete(roomId);
    if (!deletedRoom) {
      logger.warn(`Room not found for deletion with ID: ${roomId}`); // Log when room not found
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Deleted room with ID: ${roomId}`); // Log successful deletion
    res.status(200).json({ message: 'Phòng đã được xóa.' });
  } catch (error) {
    logger.error(`Error deleting room: ${error.message}`); // Log error
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật trạng thái phòng
export const updateRoomStatus = async (req, res) => {
  const roomId = req.params.id;
  const { status } = req.body;

  const validStatuses = ['available', 'booked', 'in_use', 'under_maintenance', 'cleaning', 'cleaned'];

  if (!validStatuses.includes(status)) {
    logger.warn(`Invalid status for room ${roomId}: ${status}`); // Log invalid status
    return res.status(400).json({ message: 'Trạng thái không hợp lệ.' });
  }

  try {
    const updatedRoom = await Room.findByIdAndUpdate(roomId, { status }, { new: true });
    if (!updatedRoom) {
      logger.warn(`Room not found for status update with ID: ${roomId}`); // Log when room not found
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    logger.info(`Updated status of room with ID: ${roomId} to ${status}`); // Log successful status update
    res.status(200).json(updatedRoom);
  } catch (error) {
    logger.error(`Error updating room status: ${error.message}`); // Log error
    res.status(500).json({ message: error.message });
  }
};

// Tìm phòng
export const searchRooms = async (req, res) => {
  try {
    // Ví dụ logic tìm kiếm phòng
    const query = req.query; // Nhận tham số tìm kiếm từ request
    const rooms = await Room.find(query); // Tìm phòng theo các điều kiện query
    res.status(200).send(rooms);
  } catch (error) {
    logger.error(`Error searching rooms: ${error.message}`); // Log error
    res.status(500).send({ error: 'Error searching rooms' });
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
