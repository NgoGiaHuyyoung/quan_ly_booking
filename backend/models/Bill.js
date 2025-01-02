import mongoose from 'mongoose';

const billSchema = new mongoose.Schema({
  invoiceNumber: {
    type: String,
    required: true,
  },
  customerId: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Customer',
    required: true,
  },
  amount: {
    type: Number,
    required: true,
  },
  description: {
    type: String,
    required: true,
    default: 'No description provided', // Mặc định nếu không có description
  },
  paymentDate: {
    type: Date,
    required: true,
    default: Date.now, // Mặc định nếu không có paymentDate
  },
  status: {
    type: String,
    enum: ['paid', 'unpaid', 'refunded', 'pending'],
    default: 'unpaid',
  },
  services:  [
    {
      name: { type: String, required: true },
      price: { type: Number, required: true },
    },
  ],
  transactionId: {
    type: String,
    default: null,
  },
  paymentMethod: {
    type: String,
    default: 'cod', // Mặc định phương thức thanh toán là COD
  },
  paymentStatus: {
    type: String,
    enum: ['success', 'failed', 'pending'],
    default: 'pending',
  },
  paymentTimestamp: {
    type: Date,
    default: null,
  },
  transactionDetails: {
    type: mongoose.Schema.Types.Mixed,
    default: null,
  },
});

const Bill = mongoose.model('Bill', billSchema);

export default Bill;
