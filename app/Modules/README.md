# Modules

Domain modules live under this directory.

Use this convention when a feature unit introduces a real module:

```text
app/Modules/{Domain}/
  Actions/
  DTOs/
  Enums/
  Events/
  Jobs/
  Models/
  Policies/
  Repositories/
  Services/
  Tests/
  routes.php
```

Do not add business modules before their feature specs require them.
