// staffController.js

const Staff = require('../models/Staff'); // Giả sử bạn có model Staff

// Lấy danh sách tất cả nhân viên
exports.getAllStaff = async (req, res) => {
  try {
    const staff = await Staff.find();
    res.status(200).json(staff);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Lấy thông tin nhân viên theo ID
exports.getStaffById = async (req, res) => {
  const staffId = req.params.id;
  try {
    const staff = await Staff.findById(staffId);
    if (!staff) {
      return res.status(404).json({ message: 'Không tìm thấy nhân viên.' });
    }
    res.status(200).json(staff);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tạo nhân viên mới
exports.createStaff = async (req, res) => {
  const { name, position } = req.body;
  try {
    const newStaff = new Staff({ name, position });
    await newStaff.save();
    res.status(201).json(newStaff);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật nhân viên
exports.updateStaff = async (req, res) => {
  const staffId = req.params.id;
  try {
    const updatedStaff = await Staff.findByIdAndUpdate(staffId, req.body, { new: true });
    if (!updatedStaff) {
      return res.status(404).json({ message: 'Không tìm thấy nhân viên.' });
    }
    res.status(200).json(updatedStaff);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa nhân viên
exports.deleteStaff = async (req, res) => {
  const staffId = req.params.id;
  try {
    const deletedStaff = await Staff.findByIdAndDelete(staffId);
    if (!deletedStaff) {
      return res.status(404).json({ message: 'Không tìm thấy nhân viên.' });
    }
    res.status(200).json({ message: 'Nhân viên đã bị xóa.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
