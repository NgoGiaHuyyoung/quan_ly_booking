// backend/models/VerificationCode.js
import mongoose from 'mongoose';

const VerificationCodeSchema = new mongoose.Schema({
    email: {
        type: String,
        required: true,
    },
    code: {
        type: String,
        required: true,
    },
    expiryDate: {
        type: Date,
        required: true,
    },
});

const VerificationCode = mongoose.model('VerificationCode', VerificationCodeSchema);
export default VerificationCode;
