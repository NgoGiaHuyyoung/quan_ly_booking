const express = require('express');
const { getAllReviews, createReview, deleteReview } = require('../controllers/reviewController');
const auth = require('../middlewares/auth');

const router = express.Router();

router.get('/', auth, getAllReviews);  // Route lấy tất cả reviews
router.post('/', auth, createReview);  // Route tạo review mới
router.delete('/:id', auth, deleteReview);  // Route xóa review theo ID

module.exports = router;
