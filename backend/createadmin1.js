import mongoose from 'mongoose';

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

const newPassword = "Ngogiahuy3008@@";  // Mật khẩu mới cần cập nhật

User.findByIdAndUpdate(
    '674a31c4b0efaaf4e66c2f5a',  // ID của người dùng cần cập nhật
    { password: newPassword, updatedAt: new Date() },  // Cập nhật mật khẩu và thời gian sửa đổi
    { new: true }  // Trả về người dùng đã cập nhật
)
.then(user => {
    if (user) {
        console.log("✅ Cập nhật mật khẩu thành công:", user);
    } else {
        console.log("❌ Không tìm thấy người dùng.");
    }
})
.catch(err => {
    console.error("❌ Lỗi cập nhật mật khẩu:", err);
})
.finally(() => {
    mongoose.disconnect();  // Ngắt kết nối MongoDB khi xong
});
