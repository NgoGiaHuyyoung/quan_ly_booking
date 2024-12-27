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
  // Các trường mới cho thanh toán MoMo
  transactionId: {
    type: String,  // Mã giao dịch từ MoMo
    default: null,
  },
  paymentMethod: {
    type: String,  // Phương thức thanh toán (ví dụ: MoMo, thẻ tín dụng)
    default: 'MoMo',
  },
  paymentStatus: {
    type: String,  // Trạng thái thanh toán (thành công, thất bại)
    enum: ['success', 'failed', 'pending'],
    default: 'pending',
  },
  paymentTimestamp: {
    type: Date,  // Thời gian thanh toán
    default: null,
  },
  transactionDetails: {
    type: mongoose.Schema.Types.Mixed,  // Chi tiết giao dịch nếu cần
    default: null,
  },
});

const Bill = mongoose.model('Bill', billSchema);

export default Bill;
