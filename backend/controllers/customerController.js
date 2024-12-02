import Customer from '../models/Customer.js';
import logger from '../utils/logger.js';

// Lấy danh sách tất cả khách hàng
export const getAllCustomers = async (req, res) => {
  try {
    const customers = await Customer.find();
    res.status(200).json(customers);
  } catch (error) {
    logger.error(`Error getting all customers: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Lấy thông tin khách hàng theo ID
export const getCustomerById = async (req, res) => {
  const customerId = req.params.id;
  try {
    const customer = await Customer.findById(customerId);
    if (!customer) {
      logger.warn(`Customer with ID ${customerId} not found`);
      return res.status(404).json({ message: 'Không tìm thấy khách hàng.' });
    }
    res.status(200).json(customer);
  } catch (error) {
    logger.error(`Error getting customer by ID ${customerId}: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Tạo khách hàng mới
export const createCustomer = async (req, res) => {
  const { name, email, phone } = req.body;
  try {
    // Tạo khách hàng mới mà không bao gồm bookingHistory
    const newCustomer = new Customer({ name, email, phone });
    await newCustomer.save();

    // Chuyển newCustomer sang đối tượng thường và xóa trường bookingHistory
    const customerData = newCustomer.toObject();
    delete customerData.bookingHistory;

    logger.info(`Created new customer: ${customerData.name} (${customerData.email})`);
    res.status(201).json(customerData);
  } catch (error) {
    logger.error(`Error creating new customer: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật khách hàng
export const updateCustomer = async (req, res) => {
  const customerId = req.params.id;
  try {
    const updatedCustomer = await Customer.findByIdAndUpdate(customerId, req.body, { new: true });
    if (!updatedCustomer) {
      logger.warn(`Customer with ID ${customerId} not found for update`);
      return res.status(404).json({ message: 'Không tìm thấy khách hàng.' });
    }
    logger.info(`Updated customer with ID ${customerId}`);
    res.status(200).json(updatedCustomer);
  } catch (error) {
    logger.error(`Error updating customer with ID ${customerId}: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};

// Xóa khách hàng
export const deleteCustomer = async (req, res) => {
  const customerId = req.params.id;
  try {
    const deletedCustomer = await Customer.findByIdAndDelete(customerId);
    if (!deletedCustomer) {
      logger.warn(`Customer with ID ${customerId} not found for deletion`);
      return res.status(404).json({ message: 'Không tìm thấy khách hàng.' });
    }
    logger.info(`Deleted customer with ID ${customerId}`);
    res.status(200).json({ message: 'Khách hàng đã bị xóa.' });
  } catch (error) {
    logger.error(`Error deleting customer with ID ${customerId}: ${error.message}`);
    res.status(500).json({ message: error.message });
  }
};
