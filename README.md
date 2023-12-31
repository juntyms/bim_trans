# Installation
```
git clone git@github.com:juntyms/bim_trans.git
```

```
compose update
```

> copy .env-example to .env
> change .env values as per your configuration settings

```
php artisan migrate
```

```
php artisan db:seed
```

```
php artisan serve
```



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
**Registration**
- POST /api/v1/register

**Transactions**
- GET /api/v1/transactions
- POST /api/v1/transactions
- GET /api/v1/transactions/{transaction}
- PATCH /api/v1/transactions/{transaction}
- DELETE /api/v1/transactions/{transaction}

**Payments**
- POST /api/v1/payments

**Reports**
- POST /api/v1/report

---
#### Swagger API Documentation alternative option for POSTMAN

- visit http://127.0.0.1:8000 (or your local dev url)

---
#### Testing

Update phpunit.xml to use mysql database

```xml
From

    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>

TO
    <env name="DB_CONNECTION" value="mysql"/>
    <env name="DB_DATABASE" value="testing database"/>
```

Run Test
```
php artisan test
```

---
#### Contact
- Name: Junn Eric Timoteo
- Email: juntyms@gmail.com
