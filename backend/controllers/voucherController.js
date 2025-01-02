import  Voucher  from '../models/Voucher.js'; 
import Booking from '../models/Booking.js';



export const applyVoucher = async (req, res) => {
    const { bookingId, voucherCode } = req.body;

    if (!voucherCode) {
        return res.status(400).json({ message: 'Voucher code is required' });
    }

    try {
        // Kiểm tra xem mã voucher có tồn tại không
        const voucher = await Voucher.findOne({ code: voucherCode });
        if (!voucher) {
            return res.status(404).json({ message: 'Voucher không hợp lệ' });
        }

        // Tìm đơn đặt phòng
        const booking = await Booking.findById(bookingId);
        if (!booking) {
            return res.status(404).json({ message: 'Booking not found' });
        }

        // Áp dụng voucher vào booking (Giảm giá hoặc tính toán phần discount)
        const discount = voucher.discount; // Giảm giá từ voucher
        booking.totalPrice = booking.totalPrice - discount; // Cập nhật lại giá trị tổng

        // Lưu thông tin voucher vào booking
        booking.voucherCode = voucherCode;
        booking.discount = discount;
        await booking.save();

        return res.status(200).json({ message: 'Voucher applied successfully', booking });
    } catch (error) {
        return res.status(500).json({ message: 'Error applying voucher', error: error.message });
    }
};

// Tạo voucher mới với mã tự động, discountPercentage mặc định và expirationDate tự động cập nhật
export const createVoucher = async (req, res) => {
  try {
    // Tạo mã voucher ngẫu nhiên (ví dụ: 8 ký tự ngẫu nhiên)
    const generateVoucherCode = () => {
      return Math.random().toString(36).substring(2, 10).toUpperCase();
    };
    
    // Tạo các giá trị mặc định
    const code = generateVoucherCode(); // Mã voucher tự động
    const discountPercentage = 20; // Tỷ lệ giảm giá mặc định là 20%
    const expirationDate = new Date();
    expirationDate.setMonth(expirationDate.getMonth() + 1); // Ngày hết hạn là 1 tháng sau

    // Kiểm tra xem voucher đã tồn tại hay chưa
    const existingVoucher = await Voucher.findOne({ code });
    if (existingVoucher) {
      return res.status(400).json({ message: 'Voucher code already exists.' });
    }

    // Tạo voucher mới
    const newVoucher = new Voucher({
      code,
      discountPercentage,
      expirationDate,
      isActive: true
    });

    // Lưu voucher vào cơ sở dữ liệu
    await newVoucher.save();

    // Trả về phản hồi thành công
    res.status(201).json({ message: 'Voucher created successfully!', voucher: newVoucher });
  } catch (error) {
    console.error('Error creating voucher:', error.message);
    res.status(500).json({ message: 'Error creating voucher.' });
  }
};


// Kiểm tra voucher
export const checkVoucher = async (req, res) => {
  const { code } = req.body;

  try {
    const voucher = await Voucher.findOne({ code });
    if (!voucher) {
      return res.status(404).json({ message: 'Voucher not found.' });
    }

    // Kiểm tra nếu voucher hết hạn hoặc không còn hiệu lực
    if (!voucher.isActive || new Date() > new Date(voucher.expirationDate)) {
      return res.status(400).json({ message: 'Voucher is expired or inactive.' });
    }

    res.status(200).json({ message: 'Voucher is valid.', voucher });
  } catch (error) {
    console.error('Error checking voucher:', error.message);
    res.status(500).json({ message: 'Error checking voucher.' });
  }
};

// Kích hoạt hoặc vô hiệu hóa voucher
export const toggleVoucherStatus = async (req, res) => {
  const { voucherId, status } = req.body;

  try {
    const voucher = await Voucher.findById(voucherId);
    if (!voucher) {
      return res.status(404).json({ message: 'Voucher not found.' });
    }

    voucher.isActive = status;
    await voucher.save();

    res.status(200).json({ message: `Voucher ${status ? 'activated' : 'deactivated'} successfully.` });
  } catch (error) {
    console.error('Error updating voucher status:', error.message);
    res.status(500).json({ message: 'Error updating voucher status.' });
  }
};
