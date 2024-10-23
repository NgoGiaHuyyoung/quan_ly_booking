const mongoose = require('mongoose');

const CustomerSchema = new mongoose.Schema({
  name: { type: String, required: true },
  phone: { type: String, required: true },
  address: { type: String },
  email: { type: String, required: true, unique: true },
  bookingHistory: [{ type: mongoose.Schema.Types.ObjectId, ref: 'Booking' }],
});

module.exports = mongoose.model('Customer', CustomerSchema);
