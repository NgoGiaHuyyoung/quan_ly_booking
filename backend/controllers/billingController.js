import Bill from '../models/Bill.js'; // Sử dụng import để đảm bảo ES Modules
import logger from '../utils/logger.js'; // Giả sử bạn đã tạo logger (xem thêm bên dưới)
import Payment from '../models/Payment.js';
import Service from '../models/Service.js';
import Voucher from '../models/Voucher.js';
import crypto from 'crypto';
import axios from 'axios';
import { partnerCode, accessKey, secretKey } from '../config/momoConfig.js';
console.log('MoMo Config:', { partnerCode, accessKey, secretKey });

// Hàm tạo số hóa đơn duy nhất
const generateInvoiceNumber = () => {
  const currentDate = new Date();
  const formattedDate = currentDate.toISOString().slice(0, 10).replace(/-/g, ''); // YYYYMMDD
  const randomSuffix = Math.floor(1000 + Math.random() * 9000); // 4 chữ số ngẫu nhiên
  return `INV${formattedDate}${randomSuffix}`;  // Ví dụ: INV202401011234
};

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

// Hàm tạo chữ ký
const createSignature = ({ partnerCode, accessKey, secretKey, orderId, amount, orderInfo, returnUrl, notifyUrl, requestType, ipnUrl, extraData }) => {
  const rawSignature = `partnerCode=${partnerCode}&accessKey=${accessKey}&orderId=${orderId}&amount=${amount}&orderInfo=${orderInfo}&returnUrl=${returnUrl}&notifyUrl=${notifyUrl}&requestType=${requestType}&ipnUrl=${ipnUrl}&extraData=${extraData}`;
  return crypto.createHmac('sha256', secretKey).update(rawSignature).digest('hex');
};



export const createBill = async (req, res) => {
  const { customerId, amount, description, paymentDate, status, services, paymentMethod } = req.body;

  if (!customerId || !amount || !paymentMethod) {
    return res.status(400).json({
      message: 'customerId, amount, and paymentMethod are required.',
    });
  }

  try {
    const generateInvoiceNumber = () => {
      const date = new Date();
      const randomSuffix = Math.floor(1000 + Math.random() * 9000);
      return `INV${date.toISOString().slice(0, 10).replace(/-/g, '')}${randomSuffix}`;
    };

    const generateTransactionId = () => {
      const randomId = Math.random().toString(36).substr(2, 9).toUpperCase();
      return `TXN${Date.now()}${randomId}`;
    };

    const invoiceNumber = generateInvoiceNumber();

    const validPaymentMethods = ['COD', 'Coin Blockchain'];
    if (!validPaymentMethods.includes(paymentMethod)) {
      return res.status(400).json({
        message: `Invalid payment method. Accepted methods are: ${validPaymentMethods.join(', ')}.`,
      });
    }

    // Tạo hóa đơn mới
    const newBill = new Bill({
      invoiceNumber,
      customerId,
      amount,
      description: description || 'No description provided',
      paymentDate: paymentDate || new Date(),
      status: status || 'unpaid',
      services: services || [],
      paymentMethod,
      transactionId: generateTransactionId(), // Tạo mã giao dịch ngẫu nhiên
      paymentStatus: 'pending', // Trạng thái mặc định
      paymentTimestamp: new Date(), // Lấy thời gian hiện tại
      transactionDetails: 'Transaction details not provided.', // Mặc định nội dung giao dịch
    });

    // Lưu hóa đơn vào cơ sở dữ liệu
    await newBill.save();
    logger.info('Bill created successfully:', newBill);

    res.status(201).json({
      message: 'Bill created successfully.',
      bill: newBill,
    });
  } catch (error) {
    logger.error('Error creating bill:', error);
    res.status(500).json({
      message: 'Internal server error.',
      error: error.message,
    });
  }
};

export const payment = async (req, res) => {
  const { customerId, amount, paymentMethod, serviceId, voucherCode } = req.body;

  // Kiểm tra các trường bắt buộc
  if (!customerId || !amount || !paymentMethod) {
    return res.status(400).json({
      message: 'customerId, amount, and paymentMethod are required.',
    });
  }

  try {
    const generateInvoiceNumber = () => {
      const date = new Date();
      const randomSuffix = Math.floor(1000 + Math.random() * 9000);
      return `INV${date.toISOString().slice(0, 10).replace(/-/g, '')}${randomSuffix}`;
    };
    const invoiceNumber = generateInvoiceNumber();

    const generateTransactionId = () => {
      const randomId = Math.random().toString(36).substr(2, 9).toUpperCase();
      return `TXN${Date.now()}${randomId}`;
    };

    const transactionId = generateTransactionId();
    const paymentTimestamp = new Date();

    let transactionDetails = {};

    // Xử lý chi tiết phương thức thanh toán
    if (paymentMethod === 'cod') {
      transactionDetails = { note: 'Payment upon delivery' };
    } else if (paymentMethod === 'coin') {
      transactionDetails = {
        walletAddress: '0xYourBlockchainWalletAddress',
        note: 'Send coins to the above address',
      };
    } else {
      return res.status(400).json({ message: 'Unsupported payment method.' });
    }

    // Truy vấn thông tin dịch vụ nếu serviceId được cung cấp
    let serviceDetails = [];
    if (serviceId && serviceId.length > 0) {
      const services = await Service.find({ '_id': { $in: serviceId } });

      if (services.length === 0) {
        return res.status(404).json({ message: 'No services found with the provided serviceId(s).' });
      }

      serviceDetails = services.map(service => ({
        name: service.name,
        price: service.price,
      }));
    }

    // Kiểm tra và áp dụng mã giảm giá nếu voucherCode được cung cấp
    let discountAmount = 0;
    if (voucherCode) {
      const voucher = await Voucher.findOne({ code: voucherCode });

      if (!voucher || !voucher.isActive || new Date(voucher.expirationDate) < new Date()) {
        return res.status(400).json({ message: 'Invalid or expired voucher.' });
      }

      // Áp dụng giảm giá
      discountAmount = (amount * voucher.discountPercentage) / 100;
    }

    const finalAmount = amount - discountAmount;

    // Tạo hóa đơn mới với thông tin thanh toán
    const newBill = new Bill({
      invoiceNumber,
      customerId,
      amount: finalAmount,
      paymentMethod,
      description: 'No description provided', // Mặc định
      paymentDate: paymentTimestamp, // Thời gian thanh toán
      services: serviceDetails, // Đưa thông tin dịch vụ (nếu có)
      transactionId, // Mã giao dịch
      paymentStatus: 'pending', // Trạng thái thanh toán
      paymentTimestamp, // Thời gian thanh toán
      transactionDetails, // Chi tiết giao dịch
      voucherCode: voucherCode || null, // Lưu mã voucher đã sử dụng (nếu có)
      discountAmount, // Số tiền giảm giá (nếu có)
    });

    // Lưu hóa đơn vào cơ sở dữ liệu
    await newBill.save();

    res.status(201).json({
      message: 'Payment initiated successfully.',
      bill: newBill,
      transactionDetails,
      finalAmount,
      discountAmount,
    });
  } catch (error) {
    console.error('Error initiating payment:', error);
    res.status(500).json({ message: 'Internal server error.', error: error.message });
  }
};








export const handlePayment = async (req, res) => {
  const { transactionId, paymentStatus, paymentTimestamp, signature, orderId } = req.body;

  // Kiểm tra các tham số bắt buộc
  if (!transactionId || !paymentStatus || !orderId || !signature) {
    return res.status(400).json({
      message: 'Missing required parameters in payment callback.',
    });
  }

  try {
    // Lấy thông tin hóa đơn từ database
    const bill = await Bill.findOne({ invoiceNumber: orderId });
    if (!bill) {
      return res.status(404).json({ message: 'Bill not found for given order ID.' });
    }

    // Xác thực chữ ký từ cổng thanh toán (nếu có)
    const isValidSignature = validateSignature(req.body, process.env.SECRET_KEY);
    if (!isValidSignature) {
      return res.status(400).json({ message: 'Invalid signature.' });
    }

    // Cập nhật trạng thái thanh toán
    if (paymentStatus === 'success') {
      bill.paymentStatus = 'paid';
      bill.paymentTimestamp = paymentTimestamp || new Date();
      bill.transactionDetails = 'Payment completed successfully.';
    } else {
      bill.paymentStatus = 'failed';
      bill.transactionDetails = 'Payment failed or was canceled.';
    }

    // Lưu thay đổi vào database
    await bill.save();

    // Phản hồi thành công cho cổng thanh toán
    res.status(200).json({
      message: 'Payment status updated successfully.',
      bill,
    });
  } catch (error) {
    console.error('Error handling payment:', error);
    res.status(500).json({ message: 'Internal server error.', error: error.message });
  }
};



// Hàm xác thực chữ ký (để tránh bị giả mạo)
const validateSignature = (data, signature) => {
  const signString = `${data.partnerCode}|${data.accessKey}|${data.requestId}|${data.amount}|${data.orderId}|${data.orderInfo}|${data.returnUrl}|${data.notifyUrl}`;
  const hash = crypto.createHmac('sha256', data.secretKey).update(signString).digest('hex');
  return hash === signature;
};



// Hàm xử lý thông báo IPN từ MoMo
export const ipnCallback = async (req, res) => {
  const { transactionId, orderId, paymentStatus, message, signature } = req.body;

  // Kiểm tra các tham số bắt buộc
  if (!transactionId || !orderId || !paymentStatus) {
    return res.status(400).json({ message: 'Missing required parameters in IPN callback.' });
  }

  try {
    // Kiểm tra tính hợp lệ của chữ ký (signature)
    const isValidSignature = validateSignature(req.body, signature);
    if (!isValidSignature) {
      return res.status(400).json({ message: 'Invalid signature.' });
    }

    // Xử lý thông báo IPN từ MoMo
    if (paymentStatus === 'success') {
      // Cập nhật trạng thái thanh toán trong database (bạn cần tìm và cập nhật hóa đơn theo transactionId)
      const bill = await Bill.findOne({ transactionId });

      if (!bill) {
        return res.status(404).json({ message: 'Bill not found for transaction ID.' });
      }

      // Cập nhật trạng thái thanh toán thành công
      bill.paymentStatus = 'paid';
      await bill.save();

      // Phản hồi thành công
      res.status(200).json({
        message: 'Payment successfully processed through IPN.',
        bill: bill,
      });
    } else {
      // Trường hợp thanh toán thất bại
      res.status(400).json({
        message: 'Payment failed through IPN.',
        transactionId: transactionId,
      });
    }
  } catch (error) {
    console.error('Error processing IPN callback:', error);
    res.status(500).json({ message: 'Internal server error', error: error.message });
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
