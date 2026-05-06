const crypto = require('crypto');

function signPayload(rawBody, secret) {
  return crypto.createHmac('sha256', secret).update(rawBody).digest('hex');
}

module.exports = {
  signPayload
};
