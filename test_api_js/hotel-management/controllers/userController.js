const User = require('../models/User');

// Lấy tất cả người dùng
exports.getAllUsers = async (req, res) => {
  try {
    const users = await User.find();
    res.status(200).json(users);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Lấy thông tin người dùng theo ID
exports.getUserById = async (req, res) => {
  const userId = req.params.id;
  try {
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }
    res.status(200).json(user);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tạo người dùng mới
exports.createUser = async (req, res) => {
  const { name, username, password, phone, email, role } = req.body;
  try {
    const newUser = new User({ name, username, password, phone, email, role });
    await newUser.save();
    res.status(201).json(newUser);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật thông tin người dùng
exports.updateUser = async (req, res) => {
  const userId = req.params.id;
  try {
    const updatedUser = await User.findByIdAndUpdate(userId, req.body, { new: true });
    if (!updatedUser) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }
    res.status(200).json(updatedUser);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa người dùng
exports.deleteUser = async (req, res) => {
  const userId = req.params.id;
  try {
    const deletedUser = await User.findByIdAndDelete(userId);
    if (!deletedUser) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }
    res.status(200).json({ message: 'Người dùng đã được xóa.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
