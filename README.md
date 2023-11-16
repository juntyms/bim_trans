# Installation
``git clone git@github.com:juntyms/bim_trans.git``
``compose update``
``php artisan migrate``

## Usage Postman
---
#### Authentication using Bearer Token
POST /api/v1/login

Headers
- key => `Accept`, Value=> `application/vnd.api+json`
- key => `Content-Type`, Value=> `application/vnd.api+json`

Body
- Key=> email, Value=> `<email>`
- Key=> password, Value=> `<password>`

---
#### Authentication using cookies
**Step 1**

GET /sanctum/csrf-cookie
- copy the session value

**Step 2**

POST /api/v1/login

Headers
- Key => `X-XSRF-TOKEN`, value => `<session value>`

Body
- Key=> email, Value=> `<email>`
- Key=> password, Value=> `<password>`

---

##### Other Endpoints
POST /api/v1/register

GET /api/v1/transactions
POST /api/v1/transactions
GET /api/v1/transactions/{transaction}
PATCH /api/v1/transactions/{transaction}
DELETE /api/v1/transactions/{transaction}
