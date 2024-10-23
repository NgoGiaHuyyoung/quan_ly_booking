// serviceController.js

const Service = require('../models/Service'); // Giả sử bạn có model Service

// Lấy danh sách tất cả dịch vụ
exports.getAllServices = async (req, res) => {
  try {
    const services = await Service.find();
    res.status(200).json(services);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Lấy thông tin dịch vụ theo ID
exports.getServiceById = async (req, res) => {
  const serviceId = req.params.id;
  try {
    const service = await Service.findById(serviceId);
    if (!service) {
      return res.status(404).json({ message: 'Không tìm thấy dịch vụ.' });
    }
    res.status(200).json(service);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tạo dịch vụ mới
exports.createService = async (req, res) => {
  const { name, description, price } = req.body;
  try {
    const newService = new Service({ name, description, price });
    await newService.save();
    res.status(201).json(newService);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật dịch vụ
exports.updateService = async (req, res) => {
  const serviceId = req.params.id;
  try {
    const updatedService = await Service.findByIdAndUpdate(serviceId, req.body, { new: true });
    if (!updatedService) {
      return res.status(404).json({ message: 'Không tìm thấy dịch vụ.' });
    }
    res.status(200).json(updatedService);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa dịch vụ
exports.deleteService = async (req, res) => {
  const serviceId = req.params.id;
  try {
    const deletedService = await Service.findByIdAndDelete(serviceId);
    if (!deletedService) {
      return res.status(404).json({ message: 'Không tìm thấy dịch vụ.' });
    }
    res.status(200).json({ message: 'Dịch vụ đã bị xóa.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
