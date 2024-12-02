import mongoose from 'mongoose';
import bcrypt from 'bcrypt';


mongoose.connect('mongodb://localhost:27017/hotel-management')
    .then(() => {
        console.log("✅ Kết nối MongoDB thành công!");
    })
    .catch(err => {
        console.error("❌ Lỗi kết nối MongoDB:", err);
    });

const userSchema = new mongoose.Schema({
    name: String,
    username: String,
    email: String,
    password: String,
    phone: String,
    role: String,
    verificationToken: String,
    verified: Boolean,
    createdAt: Date,
    updatedAt: Date
});

const User = mongoose.model('User', userSchema);

const password = "Ngogiahuy3008@@"; 
const saltRounds = 10;

bcrypt.hash(password, saltRounds).then(hash => {
   
    return User.findByIdAndUpdate(
        '674a31c4b0efaaf4e66c2f5a', 
        { password: hash },
        { new: true } 
    );
}).then(user => {
    if (user) {
        console.log("✅ Cập nhật mật khẩu thành công:", user);
    } else {
        console.log("❌ Không tìm thấy người dùng.");
    }
}).catch(err => {
    console.error("❌ Lỗi cập nhật mật khẩu:", err);
}).finally(() => {
    mongoose.disconnect(); 
});
