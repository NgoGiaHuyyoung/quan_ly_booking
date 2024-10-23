const express = require('express');
const mongoose = require('mongoose');
const dotenv = require('dotenv');

dotenv.config();

const app = express();
app.use(express.json());

// Kết nối MongoDB
mongoose.connect(process.env.MONGO_URI, { useNewUrlParser: true, useUnifiedTopology: true });

// Routes
app.use('/api/auth', require('./routes/authRoutes'));
app.use('/api/users', require('./routes/userRoutes'));
app.use('/api/rooms', require('./routes/roomRoutes'));
app.use('/api/bookings', require('./routes/bookingRoutes'));
app.use('/api/customers', require('./routes/customerRoutes'));
app.use('/api/staff', require('./routes/staffRoutes'));
app.use('/api/services', require('./routes/serviceRoutes'));
app.use('/api/payments', require('./routes/billingRoutes'));
app.use('/api/reviews', require('./routes/reviewRoutes'));
app.use('/api/settings', require('./routes/settingRoutes'));
app.use('/api/logs', require('./routes/logRoutes'));

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
