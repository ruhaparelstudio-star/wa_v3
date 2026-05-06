# UI Context

## UI Direction

The product is a SaaS admin dashboard for WhatsApp AI Sales Agent management.

The interface should feel:

- Clean
- Professional
- Modern SaaS
- Operational
- Easy for non-technical wedding vendors
- Not overly playful
- Not overly complex

Prioritize clarity over decoration.

## Main UI Areas

### Superadmin Dashboard

Used for:

- Tenant list
- Tenant create form
- Plan assignment
- Activation link/resend
- Tenant status management
- WA agent health overview
- Usage analytics
- Token usage
- Error rate

### Tenant Admin Dashboard

Used for:

- WhatsApp QR connect
- Conversation inbox
- Conversation detail
- Admin takeover/resume
- Knowledge management
- Package/price/FAQ settings
- Pricelist upload
- Booking link settings
- Business hours/policy/preferences
- Lead analytics

### Conversation Inbox

Should include:

- Customer phone/name
- Last message
- Stage
- Lead temperature
- Agent mode
- Handoff status
- Last message time
- Search/filter later

### Conversation Detail

Should include:

- Chat timeline
- Admin reply/takeover controls
- AI mode status
- Lead profile panel
- Admin context panel
- Decision trace summary
- Recommended next action

## Theme

Default direction: light SaaS dashboard with clean surfaces, but support future dark mode if needed.

Do not introduce a design system that conflicts with existing project CSS.

## Color Guidance

Use tokens/classes instead of hardcoded random colors.

Suggested semantic roles:

| Role | Purpose |
| --- | --- |
| Primary | Main CTA and active states |
| Success | Connected, active, sent, available |
| Warning | Trial ending, pending, needs attention |
| Danger | Error, disconnected, suspended, blocked |
| Muted | Secondary text and inactive metadata |
| Surface | Cards, panels, inbox rows |
| Border | Separators and card boundaries |

## Component Patterns

Use consistent components for:

- Button
- Card
- Table
- Form input
- Select/dropdown
- Badge/status pill
- Modal/dialog
- Tabs
- Toast/alert
- Empty state
- Loading state

## Status Badge Examples

Tenant status:

- trial
- active
- expired
- suspended

WA status:

- disconnected
- qr_pending
- connecting
- connected
- reconnecting
- failed
- banned_or_restricted

Agent mode:

- active
- paused_admin
- handoff
- limited

Lead temperature:

- cold
- warm
- hot

## Layout Rules

- Dashboard pages should use card-based sections.
- Use tables for management lists.
- Use split layout for inbox: conversation list left, chat/detail right.
- Keep forms readable with clear labels and helper text.
- Do not hide critical safety state.
- Show blocked/safe fallback reasons in admin-facing panels.

## Form UX Rules

Every form must provide:

- Clear label
- Validation error state
- Disabled/loading state on submit
- Success feedback
- Safe empty state where needed

## Mobile Rules

- Dashboard should remain usable on tablet and mobile.
- Inbox may stack vertically on small screens.
- Primary actions must remain reachable.

## AI Trace UI Direction

Decision trace display should be admin-friendly.

Show:

- Intent
- Confidence
- Extracted entities
- Current stage
- Active goal
- Allowed actions
- Blocked actions and reasons
- Validator results
- Final reply

Do not expose overly technical raw JSON by default unless in advanced/debug mode.
