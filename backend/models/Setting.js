import mongoose from 'mongoose';

const SettingSchema = new mongoose.Schema({
  hotelName: { type: String, required: true },
  address: { type: String, required: true },
  contact: { type: String, required: true },
  roomTypes: [{ type: String }],
  services: [{ type: String }],
  notifications: {
    email: { type: String },
    sms: { type: String },
  },
});

const Setting = mongoose.model('Setting', SettingSchema);

export default Setting;
