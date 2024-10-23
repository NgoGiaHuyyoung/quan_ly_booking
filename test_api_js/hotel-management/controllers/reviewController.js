// reviewController.js
const Review = require('../models/Review');

// Lấy tất cả đánh giá
exports.getAllReviews = async (req, res) => {
  try {
    const reviews = await Review.find().populate('customer');
    res.json(reviews);
  } catch (error) {
    res.status(500).json({ message: 'Error retrieving reviews' });
  }
};

// Tạo mới đánh giá
exports.createReview = async (req, res) => {
  try {
    const newReview = new Review(req.body);
    await newReview.save();
    res.status(201).json(newReview);
  } catch (error) {
    res.status(500).json({ message: 'Error creating review' });
  }
};

// Xóa đánh giá
exports.deleteReview = async (req, res) => {
  try {
    await Review.findByIdAndDelete(req.params.id);
    res.json({ message: 'Review deleted successfully' });
  } catch (error) {
    res.status(500).json({ message: 'Error deleting review' });
  }
};
