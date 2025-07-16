# Frontend Integration Guide

## Switching Frontend to PHP Backend

The React frontend can seamlessly switch to the PHP backend without any code changes. Here's how:

### Option 1: Environment Variable (Recommended)
```bash
# Create a .env file in the client directory
echo "VITE_API_BASE_URL=http://localhost:8000" > client/.env
```

### Option 2: Update the queryClient configuration
```typescript
// In client/src/lib/queryClient.ts
const API_BASE_URL = 'http://localhost:8000'; // Change from localhost:5000
```

### Option 3: Runtime Switch
```javascript
// Add this to your app configuration
const usePhpBackend = true; // Set to true to use PHP backend
const API_BASE_URL = usePhpBackend ? 'http://localhost:8000' : 'http://localhost:5000';
```

## Starting Both Servers

### Node.js Backend (Current)
```bash
npm run dev  # Runs on port 5000
```

### PHP Backend (New)
```bash
cd php/public
php -S 0.0.0.0:8000 index.php  # Runs on port 8000
```

## API Compatibility

The PHP backend maintains 100% API compatibility:

### Authentication Endpoints
- `POST /api/auth/login` ✅ Compatible
- `POST /api/auth/register` ✅ Compatible  
- `POST /api/auth/logout` ✅ Compatible
- `GET /api/auth/user` ✅ Compatible

### Transportation Request Endpoints
- `GET /api/transportation-requests` ✅ Compatible
- `POST /api/transportation-requests` ✅ Compatible
- `GET /api/transportation-requests/{id}` ✅ Compatible
- `PATCH /api/transportation-requests/{id}` ✅ Compatible

### Dashboard Endpoints
- `GET /api/dashboard/stats` ✅ Compatible
- `GET /api/dashboard/monthly-stats` ✅ Compatible
- `GET /api/dashboard/status-stats` ✅ Compatible

### All Other Endpoints
- Carriers, Routes, Shipments, Tracking ✅ All Compatible

## Database Compatibility

Both backends use the **same PostgreSQL database**:
- Same tables and schema
- Same user accounts and sessions
- Same data - no migration needed
- Can switch between backends instantly

## Session Compatibility

PHP backend uses the same session approach:
- Session-based authentication
- Same cookie handling
- Same user roles and permissions
- Login state persists between backend switches

## Testing the Switch

1. **Keep Node.js running** on port 5000
2. **Start PHP backend** on port 8000
3. **Test PHP backend** with same credentials
4. **Update frontend** to use port 8000
5. **Verify all functionality** works identically

## Production Deployment

For production, choose one backend:

### Node.js Production
- Use existing deployment setup
- Keep current workflows and processes

### PHP Production  
- Deploy to traditional PHP hosting
- Use Apache/Nginx with PHP-FPM
- Generally lower hosting costs
- Familiar to most web developers

## Performance Comparison

| Feature | Node.js | PHP |
|---------|---------|-----|
| Startup Time | Slower | Faster |
| Memory Usage | Higher | Lower |
| CPU Usage | Higher | Lower |
| Hosting Cost | Higher | Lower |
| Developer Familiarity | Medium | High |
| Scalability | Excellent | Good |

## Benefits of PHP Backend

1. **Lower hosting costs** - runs on cheaper shared hosting
2. **Familiar technology** - easier to find PHP developers
3. **Faster startup** - no Node.js initialization overhead
4. **Lower memory** - more efficient resource usage
5. **Stable technology** - mature and battle-tested
6. **Easy deployment** - simpler production setup

The choice between Node.js and PHP backends depends on your deployment preferences and team expertise!