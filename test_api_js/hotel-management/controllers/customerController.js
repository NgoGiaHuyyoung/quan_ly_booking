const Customer = require('../models/Customer');

// Lấy danh sách tất cả khách hàng
exports.getAllCustomers = async (req, res) => {
  try {
    const customers = await Customer.find();
    res.status(200).json(customers);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Lấy thông tin khách hàng theo ID
exports.getCustomerById = async (req, res) => {
  const customerId = req.params.id;
  try {
    const customer = await Customer.findById(customerId);
    if (!customer) {
      return res.status(404).json({ message: 'Không tìm thấy khách hàng.' });
    }
    res.status(200).json(customer);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tạo khách hàng mới
exports.createCustomer = async (req, res) => {
  const { name, email, phone } = req.body;
  try {
    const newCustomer = new Customer({ name, email, phone });
    await newCustomer.save();
    res.status(201).json(newCustomer);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật khách hàng
exports.updateCustomer = async (req, res) => {
  const customerId = req.params.id;
  try {
    const updatedCustomer = await Customer.findByIdAndUpdate(customerId, req.body, { new: true });
    if (!updatedCustomer) {
      return res.status(404).json({ message: 'Không tìm thấy khách hàng.' });
    }
    res.status(200).json(updatedCustomer);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa khách hàng
exports.deleteCustomer = async (req, res) => {
  const customerId = req.params.id;
  try {
    const deletedCustomer = await Customer.findByIdAndDelete(customerId);
    if (!deletedCustomer) {
      return res.status(404).json({ message: 'Không tìm thấy khách hàng.' });
    }
    res.status(200).json({ message: 'Khách hàng đã bị xóa.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
