// billingController.js

const Bill = require('../models/Bill'); // Giả sử bạn có model Bill

// Lấy danh sách tất cả các hóa đơn
exports.getAllBills = async (req, res) => {
  try {
    const bills = await Bill.find();
    res.status(200).json(bills);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Lấy thông tin hóa đơn theo ID
exports.getBillById = async (req, res) => {
  const billId = req.params.id;
  try {
    const bill = await Bill.findById(billId);
    if (!bill) {
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn.' });
    }
    res.status(200).json(bill);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Tạo hóa đơn mới
exports.createBill = async (req, res) => {
  const { customerId, amount, description } = req.body;
  try {
    const newBill = new Bill({ customerId, amount, description });
    await newBill.save();
    res.status(201).json(newBill);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật hóa đơn
exports.updateBill = async (req, res) => {
  const billId = req.params.id;
  try {
    const updatedBill = await Bill.findByIdAndUpdate(billId, req.body, { new: true });
    if (!updatedBill) {
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn.' });
    }
    res.status(200).json(updatedBill);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa hóa đơn
exports.deleteBill = async (req, res) => {
  const billId = req.params.id;
  try {
    const deletedBill = await Bill.findByIdAndDelete(billId);
    if (!deletedBill) {
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn.' });
    }
    res.status(200).json({ message: 'Hóa đơn đã bị xóa.' });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
