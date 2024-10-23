const Room = require('../models/Room');

// Lấy tất cả phòng
exports.getAllRooms = async (req, res) => {
  try {
    const rooms = await Room.find();
    res.status(200).json(rooms);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Lấy phòng theo ID
exports.getRoomById = async (req, res) => {
  const roomId = req.params.id;
  try {
    const room = await Room.findById(roomId);
    if (!room) {
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    res.status(200).json(room);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tạo phòng mới
exports.createRoom = async (req, res) => {
  const { name, type, price } = req.body;
  try {
    const newRoom = new Room({ name, type, price });
    await newRoom.save();
    res.status(201).json(newRoom);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật phòng
exports.updateRoom = async (req, res) => {
  const roomId = req.params.id;
  try {
    const updatedRoom = await Room.findByIdAndUpdate(roomId, req.body, { new: true });
    if (!updatedRoom) {
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    res.status(200).json(updatedRoom);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa phòng
exports.deleteRoom = async (req, res) => {
  const roomId = req.params.id;
  try {
    const deletedRoom = await Room.findByIdAndDelete(roomId);
    if (!deletedRoom) {
      return res.status(404).json({ message: 'Phòng không tìm thấy.' });
    }
    res.status(200).json({ message: 'Phòng đã được xóa.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
