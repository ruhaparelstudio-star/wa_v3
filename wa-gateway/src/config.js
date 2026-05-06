module.exports = {
  port: Number(process.env.PORT || 3100),
  laravelBaseUrl: process.env.LARAVEL_INTERNAL_BASE_URL || 'http://localhost:8080',
  internalSecret: process.env.WA_GATEWAY_INTERNAL_SECRET || ''
};
