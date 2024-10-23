const mongoose = require('mongoose');

const PaymentSchema = new mongoose.Schema({
  booking: { type: mongoose.Schema.Types.ObjectId, ref: 'Booking', required: true },
  amount: { type: Number, required: true },
  paymentDate: { type: Date, default: Date.now },
  method: { type: String, required: true }, // cash, credit_card, transfer
});

module.exports = mongoose.model('Payment', PaymentSchema);
