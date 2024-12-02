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
  },
  paymentDate: {
    type: Date,
    required: true,
  },
  status: {
    type: String,
    enum: ['paid', 'unpaid', 'refunded'],
    default: 'unpaid',
  },
  services: {
    type: [String],
    default: [],
  },
});

const Bill = mongoose.model('Bill', billSchema);

export default Bill;  // Đảm bảo rằng Bill được xuất khẩu dưới dạng mặc định
