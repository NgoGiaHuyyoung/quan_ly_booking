import express from 'express';
import { 
  getAllServices, 
  getServiceById, 
  createService, 
  updateService, 
  deleteService 
} from '../controllers/serviceController.js';
import { verifyToken } from '../middlewares/auth.js';

const router = express.Router();

router.get('/', verifyToken, getAllServices);
router.get('/:id', verifyToken, getServiceById);
router.post('/', verifyToken, createService);
router.put('/:id', verifyToken, updateService);
router.delete('/:id', verifyToken, deleteService);

export default router;
