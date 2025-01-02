import mongoose from 'mongoose';
import Booking from '../models/Booking.js';
import  Voucher  from '../models/Voucher.js'; 
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
  const { roomId, userId, checkInDate, checkOutDate, numberOfRooms, selectedServices, voucherCode } = req.body;
  const startDate = new Date(checkInDate);
  const endDate = new Date(checkOutDate);

  try {
    // Kiểm tra sự tồn tại của phòng và người dùng
    const roomExists = await Room.findById(roomId);
    if (!roomExists) {
      return res.status(404).json({ message: 'Room ID không tồn tại.' });
    }

    const userExists = await User.findById(userId);
    if (!userExists) {
      return res.status(404).json({ message: 'User ID không tồn tại.' });
    }

    // Kiểm tra tính khả dụng của phòng
    const isAvailable = await Booking.isRoomAvailable(new mongoose.Types.ObjectId(roomId), startDate, endDate, numberOfRooms);
    if (!isAvailable) {
      return res.status(400).json({ message: 'Phòng không khả dụng trong khoảng thời gian này.' });
    }

    // Tính giá trị tiền phòng cho số lượng phòng
    const roomPrice = roomExists.price * numberOfRooms;

    // Tính tiền dịch vụ
    let serviceTotal = 0;
    if (selectedServices && selectedServices.length > 0) {
      for (const serviceId of selectedServices) {
        const service = await Service.findById(serviceId); // Lấy thông tin dịch vụ
        if (service) {
          serviceTotal += service.price; // Cộng tiền dịch vụ vào tổng
        }
      }
    }

    // Kiểm tra voucher và áp dụng giảm giá
    let discount = 0;
    if (voucherCode) {
      const voucher = await Voucher.findOne({ code: voucherCode });
      if (voucher && voucher.isActive && new Date() < new Date(voucher.expirationDate)) {
        discount = voucher.discountPercentage ? roomPrice * (voucher.discountPercentage / 100) : voucher.discountAmount;
      } else {
        return res.status(400).json({ message: 'Voucher không hợp lệ hoặc đã hết hạn.' });
      }
    }

    // Tính tổng tiền
    const totalPrice = roomPrice + serviceTotal - discount;

    // Tạo một đơn đặt phòng mới
    const newBooking = new Booking({
      room: new mongoose.Types.ObjectId(roomId),
      customer: new mongoose.Types.ObjectId(userId),
      startDate,
      endDate,
      numberOfRooms,
      selectedServices,
      voucherCode,
      totalPrice,
      discount,
      serviceTotal,
      status: 'pending',
    });

    await newBooking.save();
    res.status(201).json(newBooking);
  } catch (error) {
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
