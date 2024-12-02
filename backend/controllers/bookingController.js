import mongoose from 'mongoose';
import Booking from '../models/Booking.js';
import Room from '../models/Room.js';
import User from '../models/User.js';
import logger from '../utils/logger.js';  // Import logger

// Lấy tất cả các đặt phòng
export const getAllBookings = async (req, res) => {
  logger.info('Request received to fetch all bookings');
  try {
    const bookings = await Booking.find();

    const formattedBookings = bookings.map(booking => {
      const options = {
        hour: '2-digit',
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour12: false 
      };

      return {
        ...booking._doc,
        startDate: `12 giờ ngày ${booking.startDate.toLocaleString('vi-VN', options)}`,
        endDate: `12 giờ ngày ${booking.endDate.toLocaleString('vi-VN', options)}`
      };
    });

    res.status(200).json(formattedBookings);
    logger.info('Successfully fetched all bookings', { bookings: formattedBookings });
  } catch (error) {
    logger.error('Error fetching all bookings', { error: error.message });
    res.status(500).json({ message: error.message });
  }
};

// Lấy đặt phòng theo ID
export const getBookingById = async (req, res) => {
  const bookingId = req.params.id;
  logger.info('Request received to fetch booking by ID', { bookingId });
  try {
    const booking = await Booking.findById(bookingId);
    if (!booking) {
      logger.warn('Booking not found', { bookingId });
      return res.status(404).json({ message: 'Không tìm thấy đặt phòng.' });
    }

    const options = {
      hour: '2-digit',
      minute: '2-digit',
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour12: false 
    };

    const formattedBooking = {
      ...booking._doc,
      startDate: `12 giờ ngày ${booking.startDate.toLocaleString('vi-VN', options)}`,
      endDate: `12 giờ ngày ${booking.endDate.toLocaleString('vi-VN', options)}`
    };

    res.status(200).json(formattedBooking);
    logger.info('Successfully fetched booking by ID', { bookingId, booking: formattedBooking });
  } catch (error) {
    logger.error('Error fetching booking by ID', { bookingId, error: error.message });
    res.status(500).json({ message: error.message });
  }
};

// Tạo đặt phòng mới
export const createBooking = async (req, res) => {
  const { roomId, userId, checkInDate, checkOutDate } = req.body;
  const startDate = new Date(checkInDate);
  const endDate = new Date(checkOutDate);

  logger.info('Request received to create new booking', { roomId, userId, checkInDate, checkOutDate });

  if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
    logger.warn('Invalid date format', { checkInDate, checkOutDate });
    return res.status(400).json({ message: 'Ngày giờ không hợp lệ.' });
  }

  if (startDate >= endDate) {
    logger.warn('Invalid date range', { startDate, endDate });
    return res.status(400).json({ message: 'Ngày trả phòng phải sau ngày nhận phòng.' });
  }

  try {
    const roomExists = await Room.findById(roomId);
    if (!roomExists) {
      logger.warn('Room not found', { roomId });
      return res.status(404).json({ message: 'Room ID không tồn tại.' });
    }

    const userExists = await User.findById(userId);
    if (!userExists) {
      logger.warn('User not found', { userId });
      return res.status(404).json({ message: 'User ID không tồn tại.' });
    }

    const isAvailable = await Booking.isRoomAvailable(new mongoose.Types.ObjectId(roomId), startDate, endDate);
    if (!isAvailable) {
      logger.warn('Room not available for booking', { roomId, startDate, endDate });
      return res.status(400).json({ message: 'Phòng không khả dụng trong khoảng thời gian này.' });
    }

    const newBooking = new Booking({
      room: new mongoose.Types.ObjectId(roomId),
      customer: new mongoose.Types.ObjectId(userId),
      startDate,
      endDate
    });

    await newBooking.save();
    res.status(201).json(newBooking);
    logger.info('Successfully created new booking', { newBooking });
  } catch (error) {
    logger.error('Error creating booking', { error: error.message });
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật đặt phòng
export const updateBooking = async (req, res) => {
  const bookingId = req.params.id;
  logger.info('Request received to update booking', { bookingId });
  try {
    const updatedBooking = await Booking.findByIdAndUpdate(bookingId, req.body, { new: true });
    if (!updatedBooking) {
      logger.warn('Booking not found for update', { bookingId });
      return res.status(404).json({ message: 'Không tìm thấy đặt phòng.' });
    }

    res.status(200).json(updatedBooking);
    logger.info('Successfully updated booking', { bookingId, updatedBooking });
  } catch (error) {
    logger.error('Error updating booking', { bookingId, error: error.message });
    res.status(500).json({ message: error.message });
  }
};

// Xóa đặt phòng
export const deleteBooking = async (req, res) => {
  const bookingId = req.params.id;
  logger.info('Request received to delete booking', { bookingId });
  try {
    const deletedBooking = await Booking.findByIdAndDelete(bookingId);
    if (!deletedBooking) {
      logger.warn('Booking not found for deletion', { bookingId });
      return res.status(404).json({ message: 'Không tìm thấy đặt phòng.' });
    }

    res.status(200).json({ message: 'Đặt phòng đã được xóa.' });
    logger.info('Successfully deleted booking', { bookingId });
  } catch (error) {
    logger.error('Error deleting booking', { bookingId, error: error.message });
    res.status(500).json({ message: error.message });
  }
};

// Chuyển phòng
export const moveRoom = async (req, res) => {
  const { bookingId, newRoomId } = req.body;
  logger.info('Request received to move room for booking', { bookingId, newRoomId });

  try {
    const booking = await Booking.findById(bookingId);
    if (!booking) {
      logger.warn('Booking not found for move', { bookingId });
      return res.status(404).json({ message: 'Đặt phòng không tìm thấy.' });
    }

    const isAvailable = await Booking.isRoomAvailable(newRoomId, booking.startDate, booking.endDate);
    if (!isAvailable) {
      logger.warn('New room not available for booking', { bookingId, newRoomId });
      return res.status(400).json({ message: 'Phòng mới không còn trống trong thời gian này.' });
    }

    booking.room = newRoomId;
    await booking.save();

    res.status(200).json({ message: 'Chuyển phòng thành công.', booking });
    logger.info('Successfully moved room for booking', { bookingId, newRoomId, booking });
  } catch (error) {
    logger.error('Error moving room', { bookingId, newRoomId, error: error.message });
    res.status(500).json({ message: error.message });
  }
};
