const mongoose = require('mongoose');

const RoomSchema = new mongoose.Schema({
  name: { type: String, required: true },
  type: { type: String, required: true },
  price: { type: Number, required: true },
  amenities: { type: String },
  status: { type: String, enum: ['available', 'booked', 'in_use', 'under_maintenance'], default: 'available' },
});

module.exports = mongoose.model('Room', RoomSchema);
