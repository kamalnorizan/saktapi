# Authentication API Documentation

This Laravel application provides a complete authentication system with registration, login, logout, and profile management using Laravel Sanctum for API token authentication.

## Available Endpoints

### 1. Register User
**POST** `/api/register`

Register a new user account.

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201 Created):**
```json
{
    "status": "success",
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2025-10-29T10:00:00.000000Z"
        },
        "access_token": "1|abcdef123456...",
        "token_type": "Bearer"
    }
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `email`: required, valid email, max 255 characters, unique
- `password`: required, string, minimum 8 characters
- `password_confirmation`: required, must match password

### 2. Login User
**POST** `/api/login`

Authenticate user and get access token.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "access_token": "2|xyz789...",
        "token_type": "Bearer"
    }
}
```

**Error Response (401 Unauthorized):**
```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```

### 3. Logout User
**POST** `/api/logout`

Revoke the current access token (logout).

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "message": "Logout successful"
}
```

### 4. Get User Profile
**GET** `/api/profile`

Get the authenticated user's profile information.

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response (200 OK):**
```json
{
    "status": "success",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2025-10-29T10:00:00.000000Z"
        }
    }
}
```

### 5. Get Current User (Alternative)
**GET** `/api/user`

Alternative endpoint to get current authenticated user.

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response (200 OK):**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2025-10-29T10:00:00.000000Z",
    "updated_at": "2025-10-29T10:00:00.000000Z"
}
```

## Error Responses

### Validation Errors (422 Unprocessable Entity)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

### Unauthorized Access (401 Unauthorized)
```json
{
    "message": "Unauthenticated."
}
```

## Usage Examples

### Using cURL

#### Register
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Get Profile
```bash
curl -X GET http://localhost:8000/api/profile \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

#### Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Using JavaScript/Fetch

#### Register
```javascript
const response = await fetch('/api/register', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    body: JSON.stringify({
        name: 'John Doe',
        email: 'john@example.com',
        password: 'password123',
        password_confirmation: 'password123'
    })
});

const data = await response.json();
```

#### Login and Store Token
```javascript
const response = await fetch('/api/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    body: JSON.stringify({
        email: 'john@example.com',
        password: 'password123'
    })
});

const data = await response.json();
if (data.status === 'success') {
    localStorage.setItem('token', data.data.access_token);
}
```

#### Authenticated Request
```javascript
const token = localStorage.getItem('token');
const response = await fetch('/api/profile', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
    }
});
```

## Testing

Run the authentication tests:

```bash
php artisan test tests/Feature/AuthTest.php
```

Or run specific test methods:

```bash
php artisan test --filter test_user_can_register
php artisan test --filter test_user_can_login
php artisan test --filter test_user_can_logout
```

## Security Features

1. **Password Hashing**: Passwords are automatically hashed using Laravel's Hash facade
2. **Token-based Authentication**: Uses Laravel Sanctum for secure API token management
3. **Input Validation**: All inputs are validated using Form Request classes
4. **Token Revocation**: Logout properly revokes the current access token
5. **Email Uniqueness**: Prevents duplicate email registrations

## Configuration

The authentication system uses Laravel Sanctum. Make sure your `.env` file has the correct database configuration and run migrations:

```bash
php artisan migrate
```

For CORS configuration, check `config/cors.php` if you're accessing from a frontend application.
