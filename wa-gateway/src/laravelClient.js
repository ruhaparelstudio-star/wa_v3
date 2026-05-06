const http = require('http');
const https = require('https');
const { URL } = require('url');
const { signPayload } = require('./signature');

function postJson(config, path, payload) {
  return new Promise((resolve, reject) => {
    if (!config.internalSecret) {
      reject(new Error('WA_GATEWAY_INTERNAL_SECRET is required'));
      return;
    }

    const rawBody = JSON.stringify(payload);
    const target = new URL(path, config.laravelBaseUrl);
    const transport = target.protocol === 'https:' ? https : http;

    const request = transport.request({
      method: 'POST',
      hostname: target.hostname,
      port: target.port || (target.protocol === 'https:' ? 443 : 80),
      path: target.pathname + target.search,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Content-Length': Buffer.byteLength(rawBody),
        'X-WA-Gateway-Secret': config.internalSecret,
        'X-WA-Signature': 'sha256=' + signPayload(rawBody, config.internalSecret)
      }
    }, (response) => {
      let body = '';

      response.setEncoding('utf8');
      response.on('data', (chunk) => {
        body += chunk;
      });
      response.on('end', () => {
        let parsedBody = null;

        try {
          parsedBody = body ? JSON.parse(body) : null;
        } catch (_error) {
          parsedBody = { raw: body };
        }

        resolve({
          statusCode: response.statusCode,
          body: parsedBody
        });
      });
    });

    request.on('error', reject);
    request.write(rawBody);
    request.end();
  });
}

module.exports = {
  postJson
};
