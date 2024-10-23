// models/Bill.js
const mongoose = require('mongoose');

const billSchema = new mongoose.Schema({
  customerId: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Customer', // Reference đến model Customer (nếu có)
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
  createdAt: {
    type: Date,
    default: Date.now,
  },
});

const Bill = mongoose.model('Bill', billSchema);

module.exports = Bill;
