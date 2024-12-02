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
