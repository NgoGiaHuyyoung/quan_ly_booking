import express from 'express';
import { getSettings, updateSettings } from '../controllers/settingController.js';

const router = express.Router();

router.get('/', getSettings);
router.put('/:id', updateSettings);

export default router; // Sử dụng default export
