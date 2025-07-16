# PHP Backend for Transportation Registry System

This is a complete PHP rewrite of the Transportation Registry System backend, originally built in Node.js/TypeScript.

## Features

- **Complete API compatibility** with the existing frontend
- **PostgreSQL integration** using PDO
- **Session-based authentication** 
- **Role-based access control**
- **RESTful API endpoints** for all system functions
- **Secure password hashing** using bcrypt
- **CORS support** for frontend integration

## Architecture

### Models
- `User.php` - User management and authentication
- `TransportationRequest.php` - Transportation request operations
- `Carrier.php` - Transport company management
- `Route.php` - Route management and optimization
- `Shipment.php` - Shipment tracking and monitoring

### API Endpoints
- `/api/auth/*` - Authentication (login, register, logout, user info)
- `/api/transportation-requests/*` - Transportation request CRUD
- `/api/dashboard/*` - Dashboard statistics and analytics
- `/api/carriers/*` - Carrier management
- `/api/routes/*` - Route management
- `/api/shipments/*` - Shipment tracking

### Configuration
- `config/database.php` - PostgreSQL database connection
- `config/session.php` - Session management and authentication helpers

## Installation

1. **Install PHP 8.2+** with PostgreSQL extensions:
   ```bash
   # Already installed via Nix packages
   ```

2. **Database Setup**:
   - Uses the same PostgreSQL database as the Node.js version
   - No schema changes required - fully compatible

3. **Start the PHP server**:
   ```bash
   cd php/public
   php -S 0.0.0.0:8000 index.php
   ```

## API Usage

### Authentication
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"admin123"}'

# Get current user
curl http://localhost:8000/api/auth/user

# Logout
curl -X POST http://localhost:8000/api/auth/logout
```

### Transportation Requests
```bash
# Get all requests
curl http://localhost:8000/api/transportation-requests

# Create new request
curl -X POST http://localhost:8000/api/transportation-requests \
  -H "Content-Type: application/json" \
  -d '{"fromCity":"Алматы","toCity":"Нур-Султан","cargoType":"Стекло","weight":"100"}'
```

### Dashboard Stats
```bash
# Get dashboard statistics
curl http://localhost:8000/api/dashboard/stats

# Get monthly statistics
curl http://localhost:8000/api/dashboard/monthly-stats
```

## Security Features

- **Password hashing** using PHP's `password_hash()` with bcrypt
- **Session management** with secure cookie settings
- **SQL injection prevention** using prepared statements
- **CORS configuration** for cross-origin requests
- **Role-based access control** matching the original system

## Integration with Frontend

The PHP backend is designed to be a drop-in replacement for the Node.js backend:

1. **Same API endpoints** and response formats
2. **Same database schema** - no migrations needed
3. **Same authentication flow** - session-based
4. **Same role permissions** and business logic

To switch the frontend to use PHP backend:
1. Update API base URL to `http://localhost:8000`
2. Restart the PHP server
3. No frontend code changes required

## Deployment

For production deployment:
1. Use Apache or Nginx as reverse proxy
2. Configure proper SSL certificates
3. Set production database credentials
4. Enable PHP OPcache for performance
5. Configure log rotation and monitoring

## Performance

The PHP backend offers:
- **Fast response times** with minimal overhead
- **Low memory footprint** compared to Node.js
- **Excellent scalability** with PHP-FPM
- **Familiar hosting environment** for traditional PHP hosting