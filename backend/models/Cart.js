import mongoose from 'mongoose';

const CartSchema = new mongoose.Schema(
  {
    customer: { 
      type: mongoose.Schema.Types.ObjectId, 
      ref: 'Customer', 
      required: true 
    },
    items: [
      {
        type: {
          type: String, 
          enum: ['booking', 'room', 'service'], 
          required: true 
        },
        itemId: { 
          type: mongoose.Schema.Types.ObjectId, 
          required: true 
        },
        quantity: { 
          type: Number, 
          required: true, 
          min: [1, 'Số lượng phải lớn hơn 0'] 
        },
        details: {
          name: { type: String, required: true },
          type: { type: String, required: true },
          price: { type: Number, required: true },
          status: { type: String, required: true },
          quantity: { type: Number, required: true },
          availableQuantity: { type: Number, required: true },
          images: { type: [String], default: [] },
          facilities: { type: [String], default: [] },
          features: { type: [String], default: [] },
          guests: { type: Number, required: true },
          rating: { type: Number, required: false },
        },
      }
    ],
    totalPrice: { 
      type: Number, 
      required: true, 
      default: 0 
    },
  },
  { timestamps: true }
);

const Cart = mongoose.model('Cart', CartSchema);
export default Cart;
