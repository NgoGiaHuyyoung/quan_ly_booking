import mongoose from 'mongoose';

const StaffSchema = new mongoose.Schema({
  name: { type: String, required: true },
  phone: { type: String, required: true, unique: true },
  position: { 
    type: String, 
    required: true, 
    enum: [
      'Housekeeping Staff', 
      'Concierge', 
      'Event Coordinator', 
      'Food and Beverage Manager', 
      'Sales Manager', 
      'Marketing Manager', 
      'Security Staff'
    ]
  },
  salary: { type: Number, required: true },
  role: { 
    type: String, 
    enum: ['Receptionist', 'Room Manager', 'Booking Manager'], 
    required: true 
  },
});

export const Staff = mongoose.model('Staff', StaffSchema);
