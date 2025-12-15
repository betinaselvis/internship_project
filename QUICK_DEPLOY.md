# Quick Deployment Guide for Railway

## Files Created/Modified ✅

| File | Type | Purpose |
|------|------|---------|
| `api/config.php` | Modified | Database config using environment variables + CORS |
| `api/helpers.php` | Modified | PDO connection now includes port |
| `api/login.php` | Modified | Added user_id to response |
| `Procfile` | New | Tells Railway how to run the app |
| `composer.json` | New | PHP version requirement |
| `.env.example` | New | Template for environment variables |
| `.gitignore` | New | Prevents committing sensitive files |
| `railway.json` | New | Railway configuration |

## Deployment Steps (5 minutes)

### Step 1: Commit changes
```bash
cd c:\xampp\htdocs\internship_project
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

### Step 2: Create Railway project
- Go to [railway.app](https://railway.app)
- Click "New Project"
- Select "Deploy from GitHub"
- Authorize and select this repository

### Step 3: Add MySQL
- In Railway dashboard, click "Add Plugin"
- Select "MySQL"
- Railway will auto-create environment variables

### Step 4: Configure database name
- Open project settings
- Find `MYSQL_DATABASE` variable
- Set value to: `guvi_intern`

### Step 5: Deploy schema
- Open Railway SSH terminal
- Run: `php apply_schema.php`
- Wait for "✅ All tables created successfully"

### Step 6: Test deployment
- Copy your Railway URL
- Visit: `https://your-url.railway.app/signup.html`
- Create an account
- Login
- Check profile loads correctly

## What Was Fixed 🔧

1. ✅ **Database connection** - Now uses Railway environment variables
2. ✅ **Login response** - Now includes user_id
3. ✅ **CORS issues** - Frontend can now call API endpoints
4. ✅ **Production ready** - All hardcoded values removed

## Troubleshooting 🆘

| Problem | Solution |
|---------|----------|
| "Connection refused" | Check MySQL plugin is added & MYSQL_DATABASE=guvi_intern |
| "Table doesn't exist" | Run `php apply_schema.php` or execute `db/schema.sql` |
| Login works but profile blank | Check browser console for errors |
| API returning 500 | Check MySQL variables in Railway dashboard |

## After Deployment

- Monitor Railway logs for errors
- Test all features (signup, login, profile update)
- Keep `.env` file locally with real credentials
- Use `.env.example` as template for other developers

## Need Help?

- Check `ERROR_REPORT.md` for detailed error information
- Check `DEPLOYMENT.md` for comprehensive guide
- Check Railway dashboard logs for specific errors

---

**Status**: ✅ Ready to Deploy!
