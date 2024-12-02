import Setting from '../models/Setting.js';
import logger from '../utils/logger.js'; // Import logger

// Lấy thông tin cài đặt
export const getSettings = async (req, res) => {
  try {
    const settings = await Setting.find();
    res.status(200).json(settings);
    logger.info('Successfully retrieved settings.');
  } catch (error) {
    res.status(500).json({ message: error.message });
    logger.error(`Error retrieving settings: ${error.message}`);
  }
};

// Cập nhật thông tin cài đặt
export const updateSettings = async (req, res) => {
  try {
    const updatedSetting = await Setting.findByIdAndUpdate(req.params.id, req.body, { new: true });
    if (!updatedSetting) {
      logger.warn(`Setting with ID ${req.params.id} not found.`);
      return res.status(404).json({ message: 'Không tìm thấy cài đặt.' });
    }
    res.status(200).json(updatedSetting);
    logger.info(`Successfully updated setting with ID: ${req.params.id}`);
  } catch (error) {
    res.status(500).json({ message: error.message });
    logger.error(`Error updating setting with ID ${req.params.id}: ${error.message}`);
  }
};
