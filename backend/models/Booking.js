import mongoose from 'mongoose';

const BookingSchema = new mongoose.Schema({
  customer: { type: mongoose.Schema.Types.ObjectId, ref: 'Customer', required: true, index: true },
  room: { type: mongoose.Schema.Types.ObjectId, ref: 'Room', required: true, index: true },
  startDate: { type: Date, required: true, index: true },
  endDate: { type: Date, required: true, index: true },
  status: { type: String, enum: ['confirmed', 'pending', 'canceled'], default: 'pending' },
});

// Static method to check if room is available
BookingSchema.statics.isRoomAvailable = async function (roomId, startDate, endDate) {
  const validRoomId = mongoose.Types.ObjectId.isValid(roomId) ? roomId : null;
  if (!validRoomId) {
    throw new Error('Invalid room ID');
  }

  console.log('Checking availability for Room ID:', validRoomId);
  console.log('Start Date:', startDate);
  console.log('End Date:', endDate);

  const bookings = await this.find({
    room: validRoomId,
    $or: [
      { startDate: { $lt: endDate, $gt: startDate } },
      { endDate: { $gt: startDate, $lt: endDate } },
      { startDate: { $lte: startDate }, endDate: { $gte: endDate } }
    ],
  });

  return bookings.length === 0;
};

// Middleware to validate dates
BookingSchema.pre('save', function (next) {
  const now = new Date();
  if (this.startDate < now) {
    return next(new Error('Start date cannot be in the past.'));
  }
  if (this.endDate <= this.startDate) {
    return next(new Error('End date must be after start date.'));
  }
  next();
});

// Exporting the model as default
const Booking = mongoose.model('Booking', BookingSchema);
export default Booking;
