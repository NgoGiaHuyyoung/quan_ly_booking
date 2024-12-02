import NodeCache from 'node-cache';

const cache = new NodeCache({ stdTTL: 600 }); // Cache tồn tại trong 10 phút

const cachingMiddleware = (req, res, next) => {
    const key = req.originalUrl;
    const cachedResponse = cache.get(key);

    if (cachedResponse) {
        // Trả về dữ liệu từ cache
        return res.status(200).json(cachedResponse);
    } else {
        // Ghi đè phương thức send để lưu dữ liệu vào cache
        res.sendResponse = res.send;
        res.send = (body) => {
            cache.set(key, JSON.parse(body));
            res.sendResponse(body);
        };
        next();
    }
};

export default cachingMiddleware;
