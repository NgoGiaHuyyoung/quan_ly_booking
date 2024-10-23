const mongoose = require('mongoose');

const StaffSchema = new mongoose.Schema({
  name: { type: String, required: true },
  phone: { type: String, required: true },
  position: { type: String, required: true },
  salary: { type: Number, required: true },
  role: { type: String, enum: ['Receptionist', 'Room Manager', 'Booking Manager'], required: true },
});

module.exports = mongoose.model('Staff', StaffSchema);
