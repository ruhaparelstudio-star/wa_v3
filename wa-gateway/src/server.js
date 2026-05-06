const express = require('express');
const config = require('./config');
const { postJson } = require('./laravelClient');

const app = express();

app.use(express.json());

app.get('/health', (_request, response) => {
  response.json({
    status: 'ok',
    service: 'wa-gateway',
    provider: 'baileys'
  });
});

app.post('/simulate/inbound', async (request, response, next) => {
  try {
    const result = await postJson(config, '/internal/whatsapp/inbound', request.body);

    response.status(result.statusCode).json(result.body);
  } catch (error) {
    next(error);
  }
});

app.post('/accounts/:waAccountId/status', async (request, response, next) => {
  try {
    const result = await postJson(
      config,
      '/internal/whatsapp/accounts/' + encodeURIComponent(request.params.waAccountId) + '/status',
      request.body
    );

    response.status(result.statusCode).json(result.body);
  } catch (error) {
    next(error);
  }
});

app.use((error, _request, response, _next) => {
  console.error('gateway request failed:', error.message);
  response.status(502).json({
    status: 'error',
    message: error.message
  });
});

if (require.main === module) {
  app.listen(config.port, () => {
    console.log('wa-gateway listening on port ' + config.port);
  });
}

module.exports = app;
