import rateLimit from 'express-rate-limit';

// Cấu hình rate limiting
const rateLimiter = rateLimit({
    windowMs: 15 * 60 * 1000, // 15 phút
    max: 100, // Tối đa 100 request mỗi IP
    message: {
        status: 429,
        message: 'Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau 15 phút.'
    }
});

export default rateLimiter;
