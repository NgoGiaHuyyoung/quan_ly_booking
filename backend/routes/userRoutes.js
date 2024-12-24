import express from 'express';
import { 
  getAllUsers, 
  getUserById, 
  createUser, 
  updateUser, 
  deleteUser, 
  changePassword 
} from '../controllers/userController.js';
import { verifyToken } from '../middlewares/auth.js'; 
import { logAction } from '../middlewares/logAction.js'; 

const router = express.Router();


router.get('/', verifyToken, logAction('GET_ALL_USERS'), getAllUsers);

router.get('/:id', verifyToken, getUserById);
// router.get('/:id', verifyToken, logAction('GET_USER_BY_ID'), getUserById);


router.post('/', verifyToken, logAction('CREATE_USER'), createUser);


router.put('/:id', verifyToken, logAction('UPDATE_USER'), updateUser);


router.delete('/:id', verifyToken, logAction('DELETE_USER'), deleteUser);


router.put('/change-password', verifyToken, logAction('CHANGE_PASSWORD'), changePassword);


export default router;











