const express = require('express');
const { getSettings, updateSettings } = require('../controllers/settingController');
const auth = require('../middlewares/auth');

const router = express.Router();

router.get('/', auth, getSettings);
router.put('/:id', auth, updateSettings);

module.exports = router;
