import nodemailer from 'nodemailer';
import logger from '../utils/logger.js';

const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: process.env.EMAIL_USER || 'ngogiahuy.utc2@gmail.com', 
    pass: process.env.EMAIL_PASS || 'lsfjobugqjizqtpw', 
  },
});

const verifyTransporter = async () => {
  try {
    await transporter.verify();
    logger.info('Email transporter đã sẵn sàng');
  } catch (error) {
    logger.error('Không thể xác minh email transporter: ' + error.message);
    throw new Error('Cấu hình email không hợp lệ');
  }
};

export const sendVerificationEmail = async (email, token) => {
  if (!email || !token) {
    logger.error('Email hoặc token không hợp lệ');
    throw new Error('Email hoặc token không hợp lệ');
  }

  const verificationUrl = `http://localhost/hbwebsite/frontend/verify-email?token=${token}`;

  const mailOptions = {
    from: process.env.EMAIL_USER || 'ngogiahuy.utc2@gmail.com',
    to: email,
    subject: 'Xác minh địa chỉ email',
    text: `Vui lòng nhấp vào liên kết để xác minh email của bạn: ${verificationUrl}`,
  };

  try {
    await verifyTransporter();
    const info = await transporter.sendMail(mailOptions);
    logger.info(`Email xác minh đã được gửi tới ${email}: ${info.response}`);
  } catch (error) {
    logger.error('Lỗi khi gửi email xác minh: ' + error.message);
    throw new Error('Không thể gửi email xác minh');
  }
};

export const sendResetPasswordEmail = async (email, token) => {
  if (!email || !token) {
    logger.error('Email hoặc token không hợp lệ');
    throw new Error('Email hoặc token không hợp lệ');
  }

  const resetPasswordUrl = `http://localhost/hbwebsite/frontend/reset-password.php?token=${token}`;

  const mailOptions = {
    from: process.env.EMAIL_USER || 'ngogiahuy.utc2@gmail.com',
    to: email,
    subject: 'Đặt lại mật khẩu của bạn',
    text: `Vui lòng nhấp vào liên kết để đặt lại mật khẩu của bạn: ${resetPasswordUrl}`,
  };

  try {
    await verifyTransporter();
    const info = await transporter.sendMail(mailOptions);
    logger.info(`Email đặt lại mật khẩu đã được gửi tới ${email}: ${info.response}`);
  } catch (error) {
    logger.error('Lỗi khi gửi email đặt lại mật khẩu: ' + error.message);
    throw new Error('Không thể gửi email đặt lại mật khẩu');
  }
};
