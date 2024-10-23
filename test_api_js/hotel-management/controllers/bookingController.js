const Booking = require('../models/Booking');

// Lấy tất cả các đặt phòng
exports.getAllBookings = async (req, res) => {
  try {
    const bookings = await Booking.find();
    res.status(200).json(bookings);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Lấy đặt phòng theo ID
exports.getBookingById = async (req, res) => {
  const bookingId = req.params.id;
  try {
    const booking = await Booking.findById(bookingId);
    if (!booking) {
      return res.status(404).json({ message: 'Không tìm thấy đặt phòng.' });
    }
    res.status(200).json(booking);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tạo đặt phòng mới
exports.createBooking = async (req, res) => {
  const { roomId, userId, checkInDate, checkOutDate } = req.body;
  try {
    const newBooking = new Booking({ roomId, userId, checkInDate, checkOutDate });
    await newBooking.save();
    res.status(201).json(newBooking);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật đặt phòng
exports.updateBooking = async (req, res) => {
  const bookingId = req.params.id;
  try {
    const updatedBooking = await Booking.findByIdAndUpdate(bookingId, req.body, { new: true });
    if (!updatedBooking) {
      return res.status(404).json({ message: 'Không tìm thấy đặt phòng.' });
    }
    res.status(200).json(updatedBooking);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa đặt phòng
exports.deleteBooking = async (req, res) => {
  const bookingId = req.params.id;
  try {
    const deletedBooking = await Booking.findByIdAndDelete(bookingId);
    if (!deletedBooking) {
      return res.status(404).json({ message: 'Không tìm thấy đặt phòng.' });
    }
    res.status(200).json({ message: 'Đặt phòng đã được xóa.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
