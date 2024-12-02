// staffController.js
import { Staff } from '../models/Staff.js'; 
import logger from '../utils/logger.js'; // Import logger

// Phương thức lấy tất cả nhân viên
export const getAllStaff = async (req, res) => {
  try {
    const staff = await Staff.find();
    res.json(staff);
    logger.info('Successfully retrieved all staff.');
  } catch (err) {
    res.status(500).json({ message: err.message });
    logger.error(`Error retrieving all staff: ${err.message}`);
  }
};

// Phương thức lấy nhân viên theo ID
export const getStaffById = async (req, res) => {
  try {
    const staff = await Staff.findById(req.params.id);
    if (!staff) {
      logger.warn(`Staff with ID ${req.params.id} not found.`);
      return res.status(404).json({ message: 'Staff not found' });
    }
    res.json(staff);
    logger.info(`Successfully retrieved staff with ID: ${req.params.id}`);
  } catch (err) {
    res.status(500).json({ message: err.message });
    logger.error(`Error retrieving staff with ID ${req.params.id}: ${err.message}`);
  }
};

// Phương thức tạo mới nhân viên
export const createStaff = async (req, res) => {
  const staff = new Staff({
    name: req.body.name,
    position: req.body.position,
    contact: req.body.contact,
  });

  try {
    const newStaff = await staff.save();
    res.status(201).json(newStaff);
    logger.info(`Successfully created new staff: ${newStaff.name}`);
  } catch (err) {
    res.status(400).json({ message: err.message });
    logger.error(`Error creating new staff: ${err.message}`);
  }
};

// Phương thức cập nhật nhân viên
export const updateStaff = async (req, res) => {
  try {
    const updatedStaff = await Staff.findByIdAndUpdate(req.params.id, req.body, { new: true });
    if (!updatedStaff) {
      logger.warn(`Staff with ID ${req.params.id} not found.`);
      return res.status(404).json({ message: 'Staff not found' });
    }
    res.json(updatedStaff);
    logger.info(`Successfully updated staff with ID: ${req.params.id}`);
  } catch (err) {
    res.status(500).json({ message: err.message });
    logger.error(`Error updating staff with ID ${req.params.id}: ${err.message}`);
  }
};

// Phương thức xóa nhân viên
export const deleteStaff = async (req, res) => {
  try {
    const staff = await Staff.findByIdAndDelete(req.params.id);
    if (!staff) {
      logger.warn(`Staff with ID ${req.params.id} not found.`);
      return res.status(404).json({ message: 'Staff not found' });
    }
    res.json({ message: 'Staff deleted' });
    logger.info(`Successfully deleted staff with ID: ${req.params.id}`);
  } catch (err) {
    res.status(500).json({ message: err.message });
    logger.error(`Error deleting staff with ID ${req.params.id}: ${err.message}`);
  }
};
