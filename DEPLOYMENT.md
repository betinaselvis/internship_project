# Railway Deployment Guide

## Fixed Issues

### 1. **Database Configuration**
   - ✅ Updated `api/config.php` to read from environment variables: `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`
   - ✅ Added CORS headers for cross-origin requests
   - ✅ Fixed PDO connection to include DB_PORT constant

### 2. **Authentication Response**
   - ✅ Updated `api/login.php` to return `user_id` in the response (was missing, breaking login flow)

### 3. **Deployment Files Created**
   - ✅ `Procfile` - Tells Railway how to run the application
   - ✅ `composer.json` - Specifies PHP version and dependencies
   - ✅ `.env.example` - Template for required environment variables
   - ✅ `.gitignore` - Prevents uploading sensitive files

## Railway Deployment Steps

### 1. Push to GitHub
```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

### 2. Create Railway Project
- Go to [railway.app](https://railway.app)
- Click "New Project"
- Select "Deploy from GitHub"
- Select your repository

### 3. Add MySQL Plugin
- In Railway dashboard, click "Add Plugin"
- Select "MySQL"
- Railway will automatically set environment variables

### 4. Set Environment Variables
- Go to project settings
- Set all variables from `.env.example`:
  - `MYSQL_HOST` (provided by MySQL plugin)
  - `MYSQL_PORT` (provided by MySQL plugin)
  - `MYSQL_DATABASE` (set to `guvi_intern`)
  - `MYSQL_USER` (provided by MySQL plugin)
  - `MYSQL_PASSWORD` (provided by MySQL plugin)

### 5. Deploy Database Schema
- SSH into Railway environment or use a deployment script
- Run: `php apply_schema.php`
- Verify all tables are created

### 6. Test the Deployment
- Visit your Railway URL
- Test signup: `https://your-app.railway.app/signup.html`
- Test login: `https://your-app.railway.app/login.html`
- Test profile: `https://your-app.railway.app/profile.html`

## API Endpoints

All API endpoints are located in `/api/`:

- **POST** `/api/register.php` - Register new user
- **POST** `/api/login.php` - Login user (returns token & user_id)
- **POST** `/api/profile_get.php` - Get user profile (requires token)
- **POST** `/api/profile_update.php` - Update profile (requires token)
- **POST** `/api/logout.php` - Logout user
- **POST** `/api/education_add.php` - Add education entry
- **POST** `/api/education_delete.php` - Delete education entry
- **POST** `/api/experience_add.php` - Add experience entry
- **POST** `/api/experience_delete.php` - Delete experience entry
- **POST** `/api/skills_add.php` - Add skill
- **POST** `/api/skills_delete.php` - Delete skill

## Remaining Issues (Minor)

### 1. Test Files
- `test.php` and `sync_files.php` contain localhost references and test utilities
- **Action**: Delete these files before deploying to production:
  ```bash
  git rm test.php sync_files.php phpinfo.php debug.php apply_schema.php
  ```

### 2. Old HTML Files
- `login_old.html`, `profile_old.html`, `profile_old2.html`, `signup_old.html` are backups
- **Action**: Delete these files before deploying:
  ```bash
  git rm login_old.html profile_old.html profile_old2.html signup_old.html
  ```

### 3. Database Schema Execution
- The `apply_schema.php` file needs to be run manually after deployment
- Consider running it locally and ensuring the schema is created in MySQL before pushing

### 4. Security Recommendations
- [ ] Add rate limiting to prevent brute force attacks
- [ ] Implement HTTPS (Railway provides free SSL)
- [ ] Add input validation to all forms
- [ ] Use prepared statements (already doing this ✅)
- [ ] Hash passwords with PASSWORD_DEFAULT (already doing this ✅)
- [ ] Set secure cookie flags if using cookies
- [ ] Implement CSRF tokens for form submissions

## Environment Variables Reference

```
MYSQL_HOST=<provided by Railway>
MYSQL_PORT=<provided by Railway>
MYSQL_DATABASE=guvi_intern
MYSQL_USER=<provided by Railway>
MYSQL_PASSWORD=<provided by Railway>
```

## Troubleshooting

### "Connection refused" error
- Ensure MySQL plugin is added to your Railway project
- Verify environment variables are set correctly
- Check database name is `guvi_intern`

### "Table doesn't exist" error
- Run `php apply_schema.php` to create tables
- OR manually execute the SQL from `db/schema.sql` in your MySQL client

### "CORS error" in browser
- CORS headers are now configured in `api/config.php`
- If still having issues, check browser console for specific error

### Login redirects but profile doesn't load
- Verify token is being stored in localStorage
- Check API response for `user_id` (now included in login response)
- Verify database sessions table exists

## Testing Commands

```bash
# Test signup
curl -X POST https://your-app.railway.app/api/register.php \
  -d "first_name=John&last_name=Doe&email=john@example.com&password=SecurePass123!"

# Test login
curl -X POST https://your-app.railway.app/api/login.php \
  -d "email=john@example.com&password=SecurePass123!"

# Test profile get (replace TOKEN with actual token)
curl -X POST https://your-app.railway.app/api/profile_get.php \
  -d "token=TOKEN"
```

## Files Modified for Deployment

1. **api/config.php** - Environment variable configuration
2. **api/helpers.php** - Updated PDO connection to use DB_PORT
3. **api/login.php** - Added user_id to response
4. **New: Procfile** - Railway runtime configuration
5. **New: composer.json** - PHP dependency specification
6. **New: .env.example** - Environment variable template
7. **New: .gitignore** - Git exclusion rules
8. **New: DEPLOYMENT.md** - This file
