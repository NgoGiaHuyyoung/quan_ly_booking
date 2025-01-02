import mongoose from 'mongoose';

const voucherSchema = new mongoose.Schema(
  {
    code: {
      type: String,
      required: true,
      unique: true,
      uppercase: true,
      trim: true
    },
    discountPercentage: {
      type: Number,
      required: true,
      min: 1, // Phải có giá trị từ 1% đến 100%
      max: 100
    },
    expirationDate: {
      type: Date,
      required: true
    },
    isActive: {
      type: Boolean,
      default: true
    }
  },
  { timestamps: true }
);

const Voucher = mongoose.model('Voucher', voucherSchema);

export default Voucher;
