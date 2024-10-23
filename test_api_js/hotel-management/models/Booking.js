const mongoose = require('mongoose');

const BookingSchema = new mongoose.Schema({
  customer: { type: mongoose.Schema.Types.ObjectId, ref: 'Customer', required: true },
  room: { type: mongoose.Schema.Types.ObjectId, ref: 'Room', required: true },
  startDate: { type: Date, required: true },
  endDate: { type: Date, required: true },
  status: { type: String, enum: ['confirmed', 'pending', 'canceled'], default: 'pending' },
});

module.exports = mongoose.model('Booking', BookingSchema);
