import bcrypt from 'bcrypt';
import User from '../models/User.js';
import Log from '../models/Log.js'; // Import model Log để ghi logs

// Lấy tất cả người dùng
export const getAllUsers = async (req, res) => {
  try {
    const users = await User.find().select('-password'); // Ẩn mật khẩu khi trả về
    res.status(200).json(users);

    // Ghi log hành động lấy danh sách người dùng
    await Log.create({
      action: 'GET_ALL_USERS',
      user: req.user.id, // ID người thực hiện hành động
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi lấy danh sách người dùng.' });
  }
};

// Lấy thông tin người dùng theo ID
export const getUserById = async (req, res) => {
  const userId = req.params.id;
  try {
    const user = await User.findById(userId).select('-password'); // Ẩn mật khẩu khi trả về
    if (!user) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }
    res.status(200).json(user);

    // Ghi log hành động lấy thông tin người dùng
    await Log.create({
      action: 'GET_USER_BY_ID',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi lấy thông tin người dùng.' });
  }
};

// Tạo người dùng mới
export const createUser = async (req, res) => {
  const { name, username, password, phone, email, role } = req.body;
  try {
    const existingUser = await User.findOne({ $or: [{ email }, { username }] });
    if (existingUser) {
      return res.status(400).json({ message: 'Email hoặc username đã tồn tại.' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    const newUser = new User({ name, username, password: hashedPassword, phone, email, role });

    await newUser.save();
    res.status(201).json({ message: 'Người dùng đã được tạo thành công.', user: newUser });

    // Ghi log hành động tạo người dùng mới
    await Log.create({
      action: 'CREATE_USER',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi tạo người dùng mới.' });
  }
};

// Cập nhật thông tin người dùng
export const updateUser = async (req, res) => {
  const userId = req.params.id;
  const { password, ...updateFields } = req.body;

  try {
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }

    if (updateFields.email || updateFields.username) {
      const existingUser = await User.findOne({
        $or: [{ email: updateFields.email }, { username: updateFields.username }],
        _id: { $ne: userId },
      });
      if (existingUser) {
        return res.status(400).json({ message: 'Email hoặc username đã tồn tại.' });
      }
    }

    if (password) {
      updateFields.password = await bcrypt.hash(password, 10);
    }

    const updatedUser = await User.findByIdAndUpdate(userId, updateFields, { new: true }).select('-password');
    res.status(200).json({ message: 'Người dùng đã được cập nhật thành công.', user: updatedUser });

    // Ghi log hành động cập nhật người dùng
    await Log.create({
      action: 'UPDATE_USER',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi cập nhật người dùng.' });
  }
};

// Xóa người dùng
export const deleteUser = async (req, res) => {
  const userId = req.params.id;
  try {
    const deletedUser = await User.findByIdAndDelete(userId);
    if (!deletedUser) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }
    res.status(200).json({ message: 'Người dùng đã được xóa.' });

    // Ghi log hành động xóa người dùng
    await Log.create({
      action: 'DELETE_USER',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi xóa người dùng.' });
  }
};

// Đổi mật khẩu của người dùng
export const changePassword = async (req, res) => {
  const { userId, oldPassword, newPassword } = req.body;

  try {
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).json({ message: 'Người dùng không tìm thấy.' });
    }

    const isMatch = await bcrypt.compare(oldPassword, user.password);
    if (!isMatch) {
      return res.status(400).json({ message: 'Mật khẩu cũ không chính xác.' });
    }

    const hashedPassword = await bcrypt.hash(newPassword, 10);
    user.password = hashedPassword;

    await user.save();
    res.status(200).json({ message: 'Mật khẩu đã được cập nhật thành công.' });

    // Ghi log hành động đổi mật khẩu người dùng
    await Log.create({
      action: 'CHANGE_PASSWORD',
      user: req.user.id,
      timestamp: new Date(),
    });
  } catch (error) {
    res.status(500).json({ message: 'Lỗi khi đổi mật khẩu.' });
  }
};
