import bcrypt from 'bcrypt';
import User from '../models/User.js';
import Log from '../models/Log.js'; 


export const getAllUsers = async (req, res) => {
  try {
    const users = await User.find().select('-password'); 
    res.status(200).json(users);

  
    await Log.create({
      action: 'GET_ALL_USERS',
      user: req.user.id, 
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi lấy danh sách người dùng.' });
  }
};


export const getUserById = async (req, res) => {
  const userId = req.params.id;
  try {
    const user = await User.findById(userId).select('-password'); 
    if (!user) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }
    res.status(200).json(user);

    
    await Log.create({
      action: 'GET_USER_BY_ID',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi lấy thông tin người dùng.' });
  }
};


export const createUser = async (req, res) => {
  const { name, username, password, phone, email, role } = req.body;
  try {
    const existingUser = await User.findOne({ $or: [{ email }, { username }] });
    if (existingUser) {
      return res.status(400).json({ message: 'Email hoặc username đã tồn tại.' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    const newUser = new User({ name, username, password: hashedPassword, phone, email, role });

    await newUser.save();
    res.status(201).json({ message: 'Người dùng đã được tạo thành công.', user: newUser });

    
    await Log.create({
      action: 'CREATE_USER',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi tạo người dùng mới.' });
  }
};

export const updateUser = async (req, res) => {
  const userId = req.params.id;
  const { password, ...updateFields } = req.body;

  try {
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }

    if (updateFields.email || updateFields.username) {
      const existingUser = await User.findOne({
        $or: [{ email: updateFields.email }, { username: updateFields.username }],
        _id: { $ne: userId },
      });
      if (existingUser) {
        return res.status(400).json({ message: 'Email hoặc username đã tồn tại.' });
      }
    }

    if (password) {
      updateFields.password = await bcrypt.hash(password, 10);
    }

    const updatedUser = await User.findByIdAndUpdate(userId, updateFields, { new: true }).select('-password');
    res.status(200).json({ message: 'Người dùng đã được cập nhật thành công.', user: updatedUser });

    await Log.create({
      action: 'UPDATE_USER',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi cập nhật người dùng.' });
  }
};

export const deleteUser = async (req, res) => {
  const userId = req.params.id;
  try {
    const deletedUser = await User.findByIdAndDelete(userId);
    if (!deletedUser) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }
    res.status(200).json({ message: 'Người dùng đã được xóa.' });

    
    await Log.create({
      action: 'DELETE_USER',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi xóa người dùng.' });
  }
};


export const changePassword = async (req, res) => {
  const { userId, oldPassword, newPassword } = req.body;

  try {
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }

    const isMatch = await bcrypt.compare(oldPassword, user.password);
    if (!isMatch) {
      return res.status(400).json({ message: 'Mật khẩu cũ không chính xác.' });
    }

    const hashedPassword = await bcrypt.hash(newPassword, 10);
    user.password = hashedPassword;

    await user.save();
    res.status(200).json({ message: 'Mật khẩu đã được cập nhật thành công.' });

    await Log.create({
      action: 'CHANGE_PASSWORD',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi đổi mật khẩu.' });
  }
};
