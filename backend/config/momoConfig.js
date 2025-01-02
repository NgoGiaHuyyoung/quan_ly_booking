import fs from 'fs';
import path from 'path';

// Đọc file config.json
const configPath = path.resolve('config/config.json');
const momoConfig = JSON.parse(fs.readFileSync(configPath, 'utf8'));

// Trích xuất thông tin MoMo
const { partnerCode, accessKey, secretKey } = momoConfig;

export { partnerCode, accessKey, secretKey };
