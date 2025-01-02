import Service from '../models/Service.js';
import logger from '../utils/logger.js'; // Import logger

// Lấy tất cả dịch vụ
export const getAllServices = async (req, res) => {
  try {
    const services = await Service.find();
    res.status(200).json(services);
    logger.info(`Successfully retrieved all services, count: ${services.length}`);
  } catch (error) {
    res.status(500).json({ message: error.message });
    logger.error(`Error retrieving all services: ${error.message}`);
  }
};

// Lấy thông tin dịch vụ theo ID
export const getServiceById = async (req, res) => {
  const serviceId = req.params.id;
  try {
    const service = await Service.findById(serviceId);
    if (!service) {
      logger.warn(`Service with ID ${serviceId} not found.`);
      return res.status(404).json({ message: 'Không tìm thấy dịch vụ.' });
    }
    res.status(200).json(service);
    logger.info(`Successfully retrieved service with ID: ${serviceId}`);
  } catch (error) {
    res.status(500).json({ message: error.message });
    logger.error(`Error retrieving service with ID ${serviceId}: ${error.message}`);
  }
};

// Tạo dịch vụ mới
export const createService = async (req, res) => {
  const { name, description, price } = req.body;
  try {
    const newService = new Service({ name, description, price });
    await newService.save();
    res.status(201).json(newService);
    logger.info(`Successfully created new service: ${newService.name}`);
  } catch (error) {
    res.status(500).json({ message: error.message });
    logger.error(`Error creating new service: ${error.message}`);
  }
};

// Cập nhật dịch vụ
export const updateService = async (req, res) => {
  const serviceId = req.params.id;
  try {
    const updatedService = await Service.findByIdAndUpdate(serviceId, req.body, { new: true });
    if (!updatedService) {
      logger.warn(`Service with ID ${serviceId} not found for update.`);
      return res.status(404).json({ message: 'Không tìm thấy dịch vụ.' });
    }
    res.status(200).json(updatedService);
    logger.info(`Successfully updated service with ID: ${serviceId}`);
  } catch (error) {
    res.status(500).json({ message: error.message });
    logger.error(`Error updating service with ID ${serviceId}: ${error.message}`);
  }
};

// Xóa dịch vụ
export const deleteService = async (req, res) => {
  const serviceId = req.params.id;
  try {
    const deletedService = await Service.findByIdAndDelete(serviceId);
    if (!deletedService) {
      logger.warn(`Service with ID ${serviceId} not found for deletion.`);
      return res.status(404).json({ message: 'Không tìm thấy dịch vụ.' });
    }
    res.status(200).json({ message: 'Dịch vụ đã bị xóa.' });
    logger.info(`Successfully deleted service with ID: ${serviceId}`);
  } catch (error) {
    res.status(500).json({ message: error.message });
    logger.error(`Error deleting service with ID ${serviceId}: ${error.message}`);
  }
};


export const saveServices = async (req, res) => {
  try {
      const userId = req.user.id;  // Lấy ID người dùng từ session hoặc JWT token
      const { services, cartId } = req.body;  // services là một mảng các ID dịch vụ và giá trị
      
      // Tìm giỏ hàng theo userId và cartId
      const cart = await Cart.findOne({ userId, _id: cartId });

      if (!cart) {
          return res.status(404).json({ message: 'Không tìm thấy giỏ hàng' });
      }

      // Thêm dịch vụ vào giỏ hàng
      cart.services = services;  // Giả sử services là một mảng chứa { serviceId, price, quantity }

      // Tính lại tổng giá (sản phẩm + dịch vụ)
      const itemTotalPrice = cart.items.reduce((total, item) => total + item.price * item.quantity, 0);
      const serviceTotalPrice = services.reduce((total, service) => total + service.price * service.quantity, 0);
      const totalPrice = itemTotalPrice + serviceTotalPrice;

      // Cập nhật tổng giá vào giỏ hàng
      cart.totalPrice = totalPrice;

      // Lưu giỏ hàng đã được cập nhật
      await cart.save();

      // Trả lại giỏ hàng đã được cập nhật
      res.status(200).json({
          message: 'Giỏ hàng đã được cập nhật',
          cart: cart
      });
  } catch (error) {
      console.error('Lỗi khi lưu dịch vụ vào giỏ hàng:', error);
      res.status(500).json({ message: 'Lỗi máy chủ' });
  }
};