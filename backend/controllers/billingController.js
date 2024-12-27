import Bill from '../models/Bill.js'; // Sử dụng import để đảm bảo ES Modules
import logger from '../utils/logger.js'; // Giả sử bạn đã tạo logger (xem thêm bên dưới)
import Payment from '../models/Payment.js';
import axios from 'axios';

// Lấy tất cả các hóa đơn
export const getAllBills = async (req, res) => {
  try {
    const bills = await Bill.find();
    res.status(200).json(bills);
  } catch (error) {
    logger.error('Error fetching all bills:', error);
    res.status(500).json({ message: 'Error fetching bills.' });
  }
};

// Lấy hóa đơn theo ID
export const getBillById = async (req, res) => {
  const billId = req.params.id;
  try {
    const bill = await Bill.findById(billId);
    if (!bill) {
      logger.warn(`Bill not found with ID: ${billId}`);
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn.' });
    }
    res.status(200).json(bill);
  } catch (error) {
    logger.error(`Error fetching bill by ID (${billId}):`, error);
    res.status(500).json({ message: 'Error fetching bill.' });
  }
};

// Tạo hóa đơn mới
export const createBill = async (req, res) => {
  const { invoiceNumber, customerId, amount, description, paymentDate, status, services } = req.body;

  if (!invoiceNumber || !customerId || !amount || !description) {
    logger.warn('Required fields missing in createBill request.');
    return res.status(400).json({ message: 'invoiceNumber, customerId, amount, and description are required.' });
  }

  try {
    // Tạo hóa đơn mới
    const newBill = new Bill({
      invoiceNumber,
      customerId,
      amount,
      description,
      paymentDate,
      status,
      services
    });

    await newBill.save();
    logger.info('Bill created successfully:', newBill);

    // Gọi MoMo API để thực hiện thanh toán
    const momoResponse = await axios.post('https://payment.momo.vn/api/transaction', {
      amount: newBill.amount,
      customerId: newBill.customerId,
      invoiceNumber: newBill.invoiceNumber,
      description: newBill.description,
      returnUrl: 'https://yourapp.com/payment/return',  // URL quay lại sau khi thanh toán
    });

    // Xử lý phản hồi từ MoMo
    if (momoResponse.data.status === 'success') {
      // Cập nhật trạng thái hóa đơn và thông tin thanh toán
      newBill.transactionId = momoResponse.data.transactionId;
      newBill.paymentStatus = 'success';
      newBill.paymentTimestamp = new Date();
      newBill.transactionDetails = momoResponse.data.details; // Chi tiết giao dịch
      newBill.status = 'paid'; // Cập nhật trạng thái hóa đơn

      await newBill.save();
      res.status(201).json({ message: 'Bill created and payment initiated successfully', bill: newBill });
    } else {
      res.status(400).json({ message: 'Failed to initiate payment with MoMo' });
    }
  } catch (error) {
    logger.error('Error creating bill or processing payment:', error);
    res.status(500).json({ message: 'Error creating bill or processing payment.' });
  }
};



export const handlePaymentCallback = async (req, res) => {
  const { transactionId, status, message, billId } = req.body;

  if (!transactionId || !status || !billId) {
    return res.status(400).json({ message: 'Transaction ID, status, and bill ID are required.' });
  }

  try {
    // Tìm hóa đơn để cập nhật
    const bill = await Bill.findById(billId);
    if (!bill) {
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn.' });
    }

    // Cập nhật trạng thái thanh toán cho hóa đơn
    if (status === 'success') {
      bill.paymentStatus = 'success';
      bill.transactionId = transactionId;
      bill.paymentTimestamp = new Date();
      bill.status = 'paid'; // Cập nhật trạng thái hóa đơn thành "đã thanh toán"
      await bill.save();

      // Lưu thông tin thanh toán vào database
      const payment = new Payment({
        amount: bill.amount,
        method: 'online',  // Giả sử là thanh toán online
        status: 'completed',
        customerId: bill.customerId,
        serviceId: bill.serviceId,
        transactionId: transactionId,
        transactionDetails: message,  // Thêm thông tin chi tiết giao dịch
        paymentStatus: 'success',
      });

      await payment.save();

      return res.status(200).json({ message: 'Payment processed successfully.' });
    } else {
      // Cập nhật trạng thái thanh toán nếu thất bại
      bill.paymentStatus = 'failed';
      bill.status = 'unpaid'; // Cập nhật trạng thái hóa đơn thành "chưa thanh toán"
      await bill.save();

      // Lưu log thất bại
      return res.status(400).json({ message: 'Payment failed.', details: message });
    }
  } catch (error) {
    return res.status(500).json({ message: 'Error processing payment callback.' });
  }
};



// Cập nhật hóa đơn
export const updateBill = async (req, res) => {
  const { id } = req.params;
  const { invoiceNumber, customerId, amount, description, paymentDate, status, services } = req.body;

  if (!invoiceNumber || !customerId || !amount || !description) {
    logger.warn('Required fields missing in updateBill request.');
    return res.status(400).json({ message: 'invoiceNumber, customerId, amount, and description are required.' });
  }

  try {
    const updatedBill = await Bill.findByIdAndUpdate(
      id,
      {
        invoiceNumber,
        customerId,
        amount,
        description,
        paymentDate,
        status,
        services
      },
      { new: true, runValidators: true }
    );

    if (!updatedBill) {
      logger.warn(`Bill not found for update with ID: ${id}`);
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn để cập nhật.' });
    }

    logger.info('Bill updated successfully:', updatedBill);
    res.status(200).json(updatedBill);
  } catch (error) {
    logger.error(`Error updating bill (${id}):`, error);
    res.status(500).json({ message: 'Error updating bill.' });
  }
};

// Xóa hóa đơn
export const deleteBill = async (req, res) => {
  const billId = req.params.id;
  try {
    const deletedBill = await Bill.findByIdAndDelete(billId);
    if (!deletedBill) {
      logger.warn(`Bill not found for deletion with ID: ${billId}`);
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn.' });
    }
    logger.info('Bill deleted successfully:', deletedBill);
    res.status(200).json({ message: 'Hóa đơn đã bị xóa.' });
  } catch (error) {
    logger.error(`Error deleting bill (${billId}):`, error);
    res.status(500).json({ message: 'Error deleting bill.' });
  }
};

// Refund hóa đơn
export const refundBill = async (req, res) => {
  const { id } = req.params;
  const { refundAmount, refundReason } = req.body; // Refund amount và lý do hoàn tiền

  if (!refundAmount || !refundReason) {
    logger.warn('Refund amount and reason are required.');
    return res.status(400).json({ message: 'Refund amount and reason are required.' });
  }

  try {
    // Tìm hóa đơn để hoàn tiền
    const bill = await Bill.findById(id);
    if (!bill) {
      logger.warn(`Bill not found for refund with ID: ${id}`);
      return res.status(404).json({ message: 'Không tìm thấy hóa đơn để hoàn tiền.' });
    }

    // Kiểm tra nếu số tiền hoàn lại không vượt quá số tiền hóa đơn
    if (refundAmount > bill.amount) {
      logger.warn(`Refund amount exceeds bill amount. Refund Amount: ${refundAmount}, Bill Amount: ${bill.amount}`);
      return res.status(400).json({ message: 'Refund amount exceeds the bill amount.' });
    }

    // Cập nhật trạng thái hóa đơn và giảm số tiền còn lại
    bill.status = 'refunded'; // Cập nhật trạng thái hóa đơn thành đã hoàn tiền
    bill.amount -= refundAmount; // Giảm số tiền của hóa đơn

    // Lưu lại hóa đơn đã cập nhật
    const updatedBill = await bill.save();

    // Tạo một mục thanh toán mới cho việc hoàn tiền
    const refundPayment = new Payment({
      billId: updatedBill._id,
      amount: -refundAmount, // Số tiền hoàn lại là âm
      paymentDate: new Date(),
      description: `Refund for bill ${updatedBill.invoiceNumber}: ${refundReason}`,
      status: 'completed',
    });

    // Lưu mục thanh toán hoàn lại
    await refundPayment.save();

    logger.info('Bill refunded successfully:', updatedBill);
    res.status(200).json({ message: 'Bill refunded successfully', bill: updatedBill, refundPayment });
  } catch (error) {
    logger.error(`Error refunding bill (${id}):`, error);
    res.status(500).json({ message: 'Error refunding bill.' });
  }
};
