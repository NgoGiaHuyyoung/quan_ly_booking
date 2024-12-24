import Cart from '../models/Cart.js';
import Room from '../models/Room.js'; // Import mô hình Room
import Service from '../models/Service.js'; // Import mô hình Service

// Lấy giỏ hàng của khách hàng hiện tại
export const getCart = async (req, res) => {
  try {
    const customerId = req.user.id; // Lấy customerId từ token
    const cart = await Cart.findOne({ customer: customerId }).populate('items.itemId');
    if (!cart) {
      return res.status(404).json({ message: 'Giỏ hàng không tồn tại' });
    }
    res.json(cart);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Thêm sản phẩm vào giỏ hàng
export const addToCart = async (req, res) => {
  const { type, itemId, quantity } = req.body;
  try {
    const customerId = req.user.id; // Lấy customerId từ token

    // Lấy thông tin chi tiết của item từ cơ sở dữ liệu
    let itemDetails;
    if (type === 'room') {
      itemDetails = await Room.findById(itemId);
    } else if (type === 'service') {
      itemDetails = await Service.findById(itemId);
    } else {
      return res.status(400).json({ message: 'Loại sản phẩm không hợp lệ' });
    }

    if (!itemDetails) {
      return res.status(404).json({ message: 'Không tìm thấy sản phẩm' });
    }

    // Lấy giỏ hàng của khách hàng
    let cart = await Cart.findOne({ customer: customerId });
    if (!cart) {
      cart = new Cart({ customer: customerId, items: [], totalPrice: 0 });
    }

    // Kiểm tra nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
    const itemIndex = cart.items.findIndex(
      (item) => item.itemId.toString() === itemId && item.type === type
    );
    if (itemIndex > -1) {
      cart.items[itemIndex].quantity += quantity;
    } else {
      // Thêm sản phẩm mới vào giỏ hàng
      cart.items.push({
        type,
        itemId,
        quantity,
        details: {
          name: itemDetails.name,
          type: itemDetails.type,
          price: itemDetails.price,
          status: itemDetails.status,
          quantity: itemDetails.quantity,
          availableQuantity: itemDetails.availableQuantity,
          images: itemDetails.images,
          facilities: itemDetails.facilities,
          features: itemDetails.features,
          guests: itemDetails.guests,
          rating: itemDetails.rating,
        },
      });
    }

    // Cập nhật tổng giá của giỏ hàng
    cart.totalPrice = cart.items.reduce(
      (total, item) => total + item.details.price * item.quantity,
      0
    );

    await cart.save();
    res.status(201).json(cart); // Trả lại giỏ hàng đã được cập nhật
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Cập nhật số lượng sản phẩm trong giỏ hàng
export const updateCart = async (req, res) => {
  const { type, itemId, quantity } = req.body;
  try {
    const customerId = req.user.id;
    const cart = await Cart.findOne({ customer: customerId });
    if (!cart) {
      return res.status(404).json({ message: 'Giỏ hàng không tồn tại' });
    }

    const itemIndex = cart.items.findIndex(
      (item) => item.itemId.toString() === itemId && item.type === type
    );
    if (itemIndex > -1) {
      if (quantity > 0) {
        cart.items[itemIndex].quantity = quantity;
      } else {
        cart.items.splice(itemIndex, 1); // Xóa sản phẩm nếu số lượng <= 0
      }
    } else {
      return res.status(404).json({ message: 'Sản phẩm không tồn tại trong giỏ hàng' });
    }

    // Cập nhật tổng giá
    cart.totalPrice = cart.items.reduce(
      (total, item) => total + item.details.price * item.quantity,
      0
    );

    await cart.save();
    res.json(cart);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Xóa sản phẩm khỏi giỏ hàng
export const removeFromCart = async (req, res) => {
  const { type, itemId } = req.body;
  try {
    const customerId = req.user.id;
    const cart = await Cart.findOne({ customer: customerId });
    if (!cart) {
      return res.status(404).json({ message: 'Giỏ hàng không tồn tại' });
    }

    cart.items = cart.items.filter(
      (item) => !(item.itemId.toString() === itemId && item.type === type)
    );

    // Cập nhật tổng giá
    cart.totalPrice = cart.items.reduce(
      (total, item) => total + item.details.price * item.quantity,
      0
    );

    await cart.save();
    res.json(cart);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
