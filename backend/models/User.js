import mongoose from 'mongoose';
import bcrypt from 'bcrypt';
import validator from 'validator';

// Định nghĩa schema cho người dùng
const userSchema = new mongoose.Schema(
  {
    name: {
      type: String,
      required: [true, 'Name is required'],
      trim: true,
    },
    username: {
      type: String,
      required: [true, 'Username is required'],
      unique: true,
      trim: true,
    },
    email: {
      type: String,
      required: [true, 'Email is required'],
      unique: true,
      lowercase: true,
      trim: true,
      validate: [validator.isEmail, 'Invalid email address'],
    },
    password: {
      type: String,
      required: true,
      minlength: 8,
      maxlength: 30,
      match: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,30}$/,
    },
    phone: {
      type: String,
      required: [true, 'Phone number is required'],
      unique: true,
      validate: {
        validator: function (value) {
          return /^\d{10}$/.test(value); 
        },
        message: 'Phone number must be 10 digits.',
      },
    },
    role: {
      type: String,
      enum: ['admin', 'staff', 'customer'],
      default: 'customer',
    },
    verificationToken: {
      type: String,
      default: null,
    },
    resetPasswordToken: {
      type: String,
      default: null,
    },
    resetPasswordExpires: {
      type: Date,
      default: null,
    },
    verified: {
      type: Boolean,
      default: false,
    },
    refreshToken: {
      type: String,
      default: null,
    },
  },
  { timestamps: true } // Tự động tạo createdAt và updatedAt
);

// Middleware mã hóa mật khẩu trước khi lưu hoặc cập nhật
userSchema.pre('save', async function (next) {
  if (this.isModified('password')) {
    this.password = await bcrypt.hash(this.password, 10);
  }
  next();
});

// Hàm so sánh mật khẩu khi đăng nhập
userSchema.methods.comparePassword = async function (candidatePassword) {
  return bcrypt.compare(candidatePassword, this.password);
};

// Hàm tạo token xác thực
userSchema.methods.generateVerificationToken = function () {
  const verificationToken = Math.random().toString(36).substr(2, 10);
  this.verificationToken = verificationToken;
  return verificationToken;
};

// Hàm tạo token reset mật khẩu
userSchema.methods.generateResetPasswordToken = function () {
  const resetPasswordToken = Math.random().toString(36).substr(2, 10);
  this.resetPasswordToken = resetPasswordToken;
  this.resetPasswordExpires = Date.now() + 3600000; // 1 giờ
  return resetPasswordToken;
};

// Xuất mô hình theo chuẩn ES Modules
const User = mongoose.model('User', userSchema);

export default User;
