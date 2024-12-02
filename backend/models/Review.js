// models/Review.js
import mongoose from 'mongoose';
const { Schema } = mongoose;

const reviewSchema = new Schema({
  customer: { type: Schema.Types.ObjectId, ref: 'Customer', required: true }, // Liên kết với bảng customer
  rating: { type: Number, required: true },
  comment: { type: String, required: true },
  createdAt: { type: Date, default: Date.now }
});

const Review = mongoose.model('Review', reviewSchema);

export default Review; // Sử dụng export default
