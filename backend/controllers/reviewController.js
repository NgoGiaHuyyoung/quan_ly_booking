import Review from '../models/Review.js'; // Sử dụng import
import logger from '../utils/logger.js'; // Import logger

// Lấy tất cả đánh giá
export const getAllReviews = async (req, res) => {
  try {
    const reviews = await Review.find().populate('customer');
    res.json(reviews);
  } catch (error) {
    logger.error(`Error retrieving reviews: ${error.message}`);
    res.status(500).json({ message: 'Error retrieving reviews' });
  }
};

// Tạo mới đánh giá
export const createReview = async (req, res) => {
  try {
    const newReview = new Review(req.body);
    await newReview.save();
    logger.info(`New review created: ${newReview._id}`);
    res.status(201).json(newReview);
  } catch (error) {
    logger.error(`Error creating review: ${error.message}`);
    res.status(500).json({ message: 'Error creating review' });
  }
};

// Xóa đánh giá
export const deleteReview = async (req, res) => {
  try {
    const deletedReview = await Review.findByIdAndDelete(req.params.id);
    if (!deletedReview) {
      return res.status(404).json({ message: 'Không tìm thấy đánh giá.' });
    }
    logger.info(`Review deleted: ${req.params.id}`);
    res.json({ message: 'Review deleted successfully' });
  } catch (error) {
    logger.error(`Error deleting review: ${error.message}`);
    res.status(500).json({ message: 'Error deleting review' });
  }
};

// Hàm cập nhật đánh giá
export const updateReview = async (req, res) => {
  try {
    const { id } = req.params;
    const { rating, comment } = req.body;

    const updatedReview = await Review.findByIdAndUpdate(
      id,
      { rating, comment },
      { new: true } // Trả về bản ghi sau khi cập nhật
    );

    if (!updatedReview) {
      return res.status(404).json({ message: 'Không tìm thấy đánh giá.' });
    }

    logger.info(`Review updated: ${updatedReview._id}`);
    res.status(200).json({
      message: 'Cập nhật đánh giá thành công.',
      review: updatedReview,
    });
  } catch (error) {
    logger.error(`Error updating review: ${error.message}`);
    res.status(500).json({ message: 'Lỗi khi cập nhật đánh giá.', error });
  }
};
