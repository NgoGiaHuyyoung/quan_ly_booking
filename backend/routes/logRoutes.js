import express from 'express';
import { getAllLogs } from '../controllers/logController.js';

const router = express.Router();

// Route lấy danh sách logs
router.get('/', (req, res) => {
  res.send('Danh sách logs');
});

// Route tạo log mới
router.post('/', (req, res) => {
  const { message, createdBy } = req.body;
  res.json({ message: 'Log created', data: { message, createdBy } });
}); 


// Xuất router dưới dạng export mặc định
export default router;
