# Testing the Authentication API

## Quick Test Commands

Here are some quick commands to test the authentication API:

### 1. Start the Laravel Development Server
```bash
php artisan serve
```

### 2. Test Registration
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 3. Test Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

Save the `access_token` from the response for the next commands.

### 4. Test Profile (Replace YOUR_TOKEN with actual token)
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 5. Test Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Testing with Postman

1. **Register User**
   - Method: POST
   - URL: `http://localhost:8000/api/register`
   - Headers: 
     - `Content-Type: application/json`
     - `Accept: application/json`
   - Body (JSON):
     ```json
     {
         "name": "Test User",
         "email": "test@example.com",
         "password": "password123",
         "password_confirmation": "password123"
     }
     ```

2. **Login User**
   - Method: POST
   - URL: `http://localhost:8000/api/login`
   - Headers: 
     - `Content-Type: application/json`
     - `Accept: application/json`
   - Body (JSON):
     ```json
     {
         "email": "test@example.com",
         "password": "password123"
     }
     ```

3. **Get Profile**
   - Method: GET
   - URL: `http://localhost:8000/api/profile`
   - Headers: 
     - `Content-Type: application/json`
     - `Accept: application/json`
     - `Authorization: Bearer {your_access_token}`

4. **Logout**
   - Method: POST
   - URL: `http://localhost:8000/api/logout`
   - Headers: 
     - `Content-Type: application/json`
     - `Accept: application/json`
     - `Authorization: Bearer {your_access_token}`
