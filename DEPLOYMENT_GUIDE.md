# 🚀 Deployment Guide - Production

## 📋 Pre-Deployment Checklist

### 1. Testing Complete
- [ ] All features tested (FINAL_CHECKLIST.md)
- [ ] All tests passed
- [ ] No critical bugs
- [ ] Performance acceptable
- [ ] Security verified

### 2. Documentation Ready
- [ ] User manual complete
- [ ] Technical documentation complete
- [ ] Installation guides ready
- [ ] Troubleshooting guides ready

### 3. Backup Strategy
- [ ] Database backup plan
- [ ] File backup plan
- [ ] Rollback plan ready
- [ ] Recovery procedure documented

---

## 🔧 Production Environment Setup

### 1. Server Requirements

#### Minimum Requirements:
- **OS**: Linux (Ubuntu 20.04+) / Windows Server
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **PHP**: 8.2+
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Memory**: 2GB RAM minimum
- **Storage**: 10GB minimum

#### PHP Extensions Required:
```
- intl
- mbstring
- json
- mysqlnd
- gd (for QR Code)
- curl
- xml
- zip
```

#### Check PHP Extensions:
```bash
php -m
```

---

### 2. Database Setup

#### Create Database:
```sql
CREATE DATABASE inventaris CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'inventaris_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON inventaris.* TO 'inventaris_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Import Database:
```bash
mysql -u inventaris_user -p inventaris < database_backup.sql
```

#### Run Migrations:
```sql
-- Run SQL files in order:
source ALTER_TABLE_BARANG_ADD_FOTO.sql
source ALTER_TABLE_PINJAM_ADD_KONDISI.sql
source ALTER_TABLE_PINJAM_ADD_DEADLINE.sql
```

---

### 3. File Deployment

#### Upload Files:
```bash
# Via FTP/SFTP
# Upload all files to: /var/www/html/inventaris/

# Or via Git
cd /var/www/html/
git clone [repository-url] inventaris
cd inventaris
```

#### Set Permissions:
```bash
# Linux
chmod -R 755 /var/www/html/inventaris
chmod -R 777 /var/www/html/inventaris/writable
chmod -R 755 /var/www/html/inventaris/public/uploads

# Set owner
chown -R www-data:www-data /var/www/html/inventaris
```

---

### 4. Install Dependencies

#### Composer Install:
```bash
cd /var/www/html/inventaris
composer install --no-dev --optimize-autoloader
```

#### Install QR Code Library:
```bash
composer require endroid/qr-code
```

---

### 5. Environment Configuration

#### Copy .env file:
```bash
cp env .env
```

#### Edit .env:
```env
# Environment
CI_ENVIRONMENT = production

# Base URL
app.baseURL = 'https://yourdomain.com/'

# Database
database.default.hostname = localhost
database.default.database = inventaris
database.default.username = inventaris_user
database.default.password = strong_password_here
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306

# Encryption
encryption.key = [generate-32-character-key]

# Session
app.sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler'
app.sessionCookieName = 'ci_session'
app.sessionExpiration = 7200
app.sessionSavePath = null
app.sessionMatchIP = false
app.sessionTimeToUpdate = 300
app.sessionRegenerateDestroy = false

# Security
app.CSRFProtection = true
app.CSRFTokenName = 'csrf_token_name'
app.CSRFHeaderName = 'X-CSRF-TOKEN'
app.CSRFCookieName = 'csrf_cookie_name'
app.CSRFExpire = 7200
app.CSRFRegenerate = true
app.CSRFRedirect = true
app.CSRFSameSite = 'Lax'

# Cookie
cookie.prefix = ''
cookie.expires = 0
cookie.path = '/'
cookie.domain = ''
cookie.secure = true
cookie.httponly = true
cookie.samesite = 'Lax'
```

#### Generate Encryption Key:
```bash
php spark key:generate
```

---

### 6. Web Server Configuration

#### Apache (.htaccess):
```apache
# Already included in public/.htaccess
# Verify mod_rewrite enabled:
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Apache VirtualHost:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/inventaris/public
    
    <Directory /var/www/html/inventaris/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/inventaris_error.log
    CustomLog ${APACHE_LOG_DIR}/inventaris_access.log combined
</VirtualHost>
```

#### Nginx Configuration:
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/html/inventaris/public;
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    access_log /var/log/nginx/inventaris_access.log;
    error_log /var/log/nginx/inventaris_error.log;
}
```

---

### 7. SSL Certificate (HTTPS)

#### Using Let's Encrypt:
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get Certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

#### Update .env:
```env
app.baseURL = 'https://yourdomain.com/'
cookie.secure = true
```

---

### 8. Create Required Folders

```bash
# Create upload folders
mkdir -p public/uploads/barang
mkdir -p public/uploads/qrcodes
mkdir -p public/uploads/placeholder

# Set permissions
chmod -R 755 public/uploads
chown -R www-data:www-data public/uploads

# Create writable folders
chmod -R 777 writable
```

---

### 9. Enable PHP GD Extension

#### Check if enabled:
```bash
php -m | grep gd
```

#### Enable if not:
```bash
# Ubuntu/Debian
sudo apt install php8.2-gd
sudo systemctl restart apache2

# Or edit php.ini
sudo nano /etc/php/8.2/apache2/php.ini
# Uncomment: extension=gd
sudo systemctl restart apache2
```

---

### 10. Optimize for Production

#### PHP Configuration (php.ini):
```ini
# Performance
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2

# Security
expose_php=Off
display_errors=Off
log_errors=On
error_log=/var/log/php_errors.log

# Limits
memory_limit=256M
upload_max_filesize=10M
post_max_size=10M
max_execution_time=60
```

#### CodeIgniter Optimization:
```bash
# Clear cache
php spark cache:clear

# Optimize autoloader
composer dump-autoload --optimize --no-dev
```

---

## 🧪 Post-Deployment Testing

### 1. Smoke Test
- [ ] Homepage loads
- [ ] Login works
- [ ] Dashboard loads
- [ ] Database connection OK
- [ ] No PHP errors

### 2. Feature Test
- [ ] All CRUD operations work
- [ ] File uploads work
- [ ] QR Code generation works
- [ ] Export Excel works
- [ ] Email notifications work (if any)

### 3. Performance Test
- [ ] Page load < 2 seconds
- [ ] Database queries optimized
- [ ] No memory leaks
- [ ] Server resources OK

### 4. Security Test
- [ ] HTTPS enabled
- [ ] CSRF protection active
- [ ] XSS protection active
- [ ] SQL injection protected
- [ ] File upload validation works

---

## 📊 Monitoring Setup

### 1. Error Logging

#### Check Logs:
```bash
# Application logs
tail -f writable/logs/log-*.log

# Apache logs
tail -f /var/log/apache2/inventaris_error.log

# PHP logs
tail -f /var/log/php_errors.log
```

### 2. Performance Monitoring

#### Tools:
- New Relic
- Datadog
- Google Analytics
- Server monitoring (htop, netdata)

### 3. Uptime Monitoring

#### Services:
- UptimeRobot
- Pingdom
- StatusCake

---

## 🔄 Backup Strategy

### 1. Database Backup

#### Daily Backup Script:
```bash
#!/bin/bash
# /usr/local/bin/backup_db.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/database"
DB_NAME="inventaris"
DB_USER="inventaris_user"
DB_PASS="strong_password_here"

mkdir -p $BACKUP_DIR
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/inventaris_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
```

#### Cron Job:
```bash
# Run daily at 2 AM
0 2 * * * /usr/local/bin/backup_db.sh
```

### 2. File Backup

#### Backup Script:
```bash
#!/bin/bash
# /usr/local/bin/backup_files.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/files"
APP_DIR="/var/www/html/inventaris"

mkdir -p $BACKUP_DIR
tar -czf $BACKUP_DIR/inventaris_files_$DATE.tar.gz \
    $APP_DIR/public/uploads \
    $APP_DIR/.env

# Keep only last 30 days
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

---

## 🔙 Rollback Plan

### If Deployment Fails:

#### 1. Restore Database:
```bash
gunzip < /backups/database/inventaris_YYYYMMDD_HHMMSS.sql.gz | mysql -u inventaris_user -p inventaris
```

#### 2. Restore Files:
```bash
tar -xzf /backups/files/inventaris_files_YYYYMMDD_HHMMSS.tar.gz -C /
```

#### 3. Revert Code:
```bash
cd /var/www/html/inventaris
git checkout [previous-commit-hash]
composer install
```

---

## 📝 Maintenance

### Regular Tasks:

#### Daily:
- [ ] Check error logs
- [ ] Monitor server resources
- [ ] Check backup success

#### Weekly:
- [ ] Review activity logs
- [ ] Check disk space
- [ ] Update dependencies (if needed)

#### Monthly:
- [ ] Security updates
- [ ] Performance review
- [ ] Backup verification
- [ ] User feedback review

---

## 🆘 Troubleshooting

### Common Issues:

#### 1. 500 Internal Server Error
```bash
# Check logs
tail -f /var/log/apache2/inventaris_error.log

# Check permissions
ls -la /var/www/html/inventaris/writable

# Check .env file
cat /var/www/html/inventaris/.env
```

#### 2. Database Connection Error
```bash
# Test connection
mysql -u inventaris_user -p inventaris

# Check .env database settings
# Verify user permissions
```

#### 3. File Upload Not Working
```bash
# Check folder permissions
ls -la /var/www/html/inventaris/public/uploads

# Check PHP upload settings
php -i | grep upload

# Check disk space
df -h
```

#### 4. QR Code Not Generating
```bash
# Check GD extension
php -m | grep gd

# Check folder permissions
ls -la /var/www/html/inventaris/public/uploads/qrcodes

# Check error logs
tail -f writable/logs/log-*.log
```

---

## 📞 Support Contacts

### Technical Support:
- Developer: [Name]
- Email: [Email]
- Phone: [Phone]
- Emergency: [Emergency Contact]

### Hosting Support:
- Provider: [Hosting Provider]
- Support: [Support Contact]
- Account: [Account ID]

---

## ✅ Deployment Checklist

### Pre-Deployment
- [ ] All tests passed
- [ ] Documentation complete
- [ ] Backup created
- [ ] Rollback plan ready

### Deployment
- [ ] Server setup complete
- [ ] Database configured
- [ ] Files uploaded
- [ ] Dependencies installed
- [ ] Environment configured
- [ ] Web server configured
- [ ] SSL enabled
- [ ] Folders created
- [ ] Permissions set

### Post-Deployment
- [ ] Smoke test passed
- [ ] Feature test passed
- [ ] Performance test passed
- [ ] Security test passed
- [ ] Monitoring setup
- [ ] Backup configured
- [ ] Documentation updated

### Go-Live
- [ ] DNS configured
- [ ] Users notified
- [ ] Training completed
- [ ] Support ready

---

**🚀 READY FOR PRODUCTION!**

*Deployment Date: __________*
*Deployed By: __________*
*Status: __________*
