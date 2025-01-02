import mongoose from 'mongoose';

const paymentSchema = new mongoose.Schema({
  amount: {
    type: Number,
    required: true,
  },
  method: {
    type: String,
    enum: ['cash', 'card', 'online'],
    required: true,
  },
  status: {
    type: String,
    enum: ['pending', 'unpaid', 'refunded'],
    default: 'pending', // Mặc định là 'pending'
  },
  customerId: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Customer',
    required: true,
  },
  serviceId: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Service',
    required: true,
  },
  paymentDate: {
    type: Date,
    default: Date.now, // Mặc định là thời gian hiện tại
  },
});

const Payment = mongoose.model('Payment', paymentSchema);

export default Payment;
