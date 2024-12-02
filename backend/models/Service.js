import mongoose from 'mongoose';

const ServiceSchema = new mongoose.Schema({
  name: { type: String, required: true, trim: true },
  description: { type: String, required: true },
  price: { type: Number, required: true, min: [0, 'Giá không thể âm'] }
}, { timestamps: true });

const Service = mongoose.model('Service', ServiceSchema);
export default Service;
