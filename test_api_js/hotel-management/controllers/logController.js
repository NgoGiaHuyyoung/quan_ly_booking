const Log = require('../models/Log');

// Lấy nhật ký hoạt động
exports.getLogs = async (req, res) => {
  const logs = await Log.find().populate('user');
  res.json(logs);
};
