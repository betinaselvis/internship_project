# Railway Deployment - Summary of Fixes

## Critical Issues Fixed ✅

### 1. **Database Configuration Error**
- **Problem**: `api/helpers.php` was referencing constants `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` that were never defined
- **Solution**: Updated `api/config.php` to define these constants from environment variables:
  ```php
  define('DB_HOST', getenv('MYSQL_HOST') ?: 'localhost');
  define('DB_PORT', getenv('MYSQL_PORT') ?: 3306);
  define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'guvi_intern');
  define('DB_USER', getenv('MYSQL_USER') ?: 'root');
  define('DB_PASS', getenv('MYSQL_PASSWORD') ?: '');
  ```
- **Impact**: App will now correctly connect to Railway's MySQL database

### 2. **Missing user_id in Login Response**
- **Problem**: `api/login.php` returned only token, but `assets/js/login.js` expected `user_id` in response
- **Solution**: Updated login response to include `user_id`:
  ```php
  jsonResponse(['success'=>true, 'token'=>$token, 'user_id'=>$user['id']]);
  ```
- **Impact**: Login flow will work properly, profile page will load with correct user

### 3. **PDO Connection Missing Port**
- **Problem**: Database connection string didn't include port parameter
- **Solution**: Updated `api/helpers.php` to use DB_PORT constant:
  ```php
  $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
  ```
- **Impact**: Connection works with custom ports (Railway's MySQL uses non-standard ports)

### 4. **Missing CORS Headers**
- **Problem**: Frontend and API on different domains would fail with CORS errors
- **Solution**: Added CORS headers to `api/config.php`:
  ```php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type');
  ```
- **Impact**: API will be accessible from any domain

## Files Created for Deployment ✅

### 1. **Procfile**
- Tells Railway how to run the PHP application
- Uses Apache with FastCGI

### 2. **composer.json**
- Specifies PHP version requirement (^8.0)
- Railway uses this to detect PHP project and install dependencies

### 3. **.env.example**
- Template showing all required environment variables
- Users know what to configure in Railway dashboard

### 4. **.gitignore**
- Prevents committing `.env` files with sensitive credentials
- Standard exclusions for vendor/, cache/, IDE files

### 5. **DEPLOYMENT.md**
- Complete step-by-step guide for Railway deployment
- Includes testing instructions and troubleshooting

## Files Modified for Deployment ✅

| File | Changes |
|------|---------|
| `api/config.php` | Rewrote to use environment variables, added CORS headers |
| `api/helpers.php` | Added DB_PORT to PDO connection string |
| `api/login.php` | Added `user_id` to JSON response |

## Testing Checklist

Before deploying to Railway:

- [ ] Run locally with environment variables set
- [ ] Test signup flow: `http://localhost/internship_project/signup.html`
- [ ] Test login flow: `http://localhost/internship_project/login.html`
- [ ] Test profile page: `http://localhost/internship_project/profile.html`
- [ ] Check browser console for CORS errors
- [ ] Verify token is stored in localStorage after login

## Optional Cleanup (Before Deploy)

Delete these files that are only needed for local development:

```bash
git rm test.php sync_files.php phpinfo.php debug.php apply_schema.php
git rm login_old.html profile_old.html profile_old2.html signup_old.html
git commit -m "Remove development and backup files"
git push
```

## Quick Deployment Steps

1. **Push changes to GitHub**
   ```bash
   git add .
   git commit -m "Prepare for Railway deployment"
   git push
   ```

2. **Create Railway project**
   - Go to railway.app
   - New Project → Deploy from GitHub
   - Select repository

3. **Add MySQL plugin**
   - Click "Add Plugin" → "MySQL"

4. **Deploy database schema**
   - Option A: Run `php apply_schema.php` via SSH
   - Option B: Execute `db/schema.sql` manually in MySQL client

5. **Test the deployment**
   - Visit your Railway URL and test signup/login/profile

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| "Connection refused" | Verify MySQL plugin is added, check env vars |
| "Table doesn't exist" | Run database schema SQL script |
| CORS error in browser | CORS headers are now configured ✅ |
| Login redirects but profile blank | Verify `user_id` is in login response ✅ |
| API returns 500 errors | Check MySQL environment variables are set |

## Security Notes

✅ Already implemented:
- Password hashing with `PASSWORD_DEFAULT`
- Prepared statements to prevent SQL injection
- Secure token generation with `random_bytes()`

⚠️ Recommended additions:
- Rate limiting on signup/login endpoints
- HTTPS (Railway provides free SSL)
- Input validation improvements
- CSRF token implementation
- Session timeout enforcement

## Environment Variables for Railway

```
MYSQL_HOST=<auto-provided by Railway MySQL>
MYSQL_PORT=<auto-provided by Railway MySQL>
MYSQL_DATABASE=guvi_intern
MYSQL_USER=<auto-provided by Railway MySQL>
MYSQL_PASSWORD=<auto-provided by Railway MySQL>
```

Note: Railway MySQL plugin automatically sets most variables. You only need to set `MYSQL_DATABASE=guvi_intern`.
