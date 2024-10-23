const Setting = require('../models/Setting');

// Lấy thông tin cài đặt
exports.getSettings = async (req, res) => {
  const settings = await Setting.find();
  res.json(settings);
};

// Cập nhật thông tin cài đặt
exports.updateSettings = async (req, res) => {
  const updatedSetting = await Setting.findByIdAndUpdate(req.params.id, req.body, { new: true });
  res.json(updatedSetting);
};
