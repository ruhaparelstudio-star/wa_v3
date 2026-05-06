# WA Gateway

Node.js boundary for WhatsApp/Baileys ownership.

Current skeleton:

- `GET /health` returns gateway health.
- `POST /simulate/inbound` forwards a simulated inbound payload to Laravel.
- `POST /accounts/:waAccountId/status` forwards session/account status to Laravel.

The gateway signs Laravel callbacks with:

- `X-WA-Gateway-Secret`
- `X-WA-Signature: sha256=<hmac>`

Business rules, tenant policies, AI decisions, pricing, booking, and response composition stay in Laravel.
