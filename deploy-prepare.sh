#!/bin/bash

# Script untuk mempersiapkan aplikasi untuk deployment
# Jalankan: bash deploy-prepare.sh

echo "=========================================="
echo "  PERSIAPAN DEPLOYMENT PRODUCTION"
echo "=========================================="
echo ""

# 1. Install dependencies production
echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader
echo "✅ Dependencies installed"
echo ""

# 2. Clear cache
echo "🧹 Clearing cache..."
php spark cache:clear
echo "✅ Cache cleared"
echo ""

# 3. Generate encryption key
echo "🔑 Generating encryption key..."
echo "Copy key ini ke .env di production:"
php spark key:generate --show
echo ""

# 4. Test production mode
echo "🧪 Testing production mode..."
echo "Pastikan CI_ENVIRONMENT=production di .env"
echo "Kemudian jalankan: php spark serve"
echo ""

# 5. Export database
echo "💾 Exporting database..."
read -p "Enter database name: " dbname
read -p "Enter database user: " dbuser
read -sp "Enter database password: " dbpass
echo ""

mysqldump -u "$dbuser" -p"$dbpass" "$dbname" > database_export_$(date +%Y%m%d_%H%M%S).sql

if [ $? -eq 0 ]; then
    echo "✅ Database exported successfully"
else
    echo "❌ Database export failed"
fi
echo ""

# 6. Create archive
echo "📦 Creating deployment archive..."
zip -r inventaris_$(date +%Y%m%d_%H%M%S).zip . \
    -x "*.git*" \
    -x "writable/logs/*" \
    -x "writable/cache/*" \
    -x ".env" \
    -x "*.sh" \
    -x "node_modules/*" \
    -x "tests/*"

if [ $? -eq 0 ]; then
    echo "✅ Archive created successfully"
else
    echo "❌ Archive creation failed"
fi
echo ""

echo "=========================================="
echo "  PERSIAPAN SELESAI!"
echo "=========================================="
echo ""
echo "Langkah selanjutnya:"
echo "1. Upload file zip ke hosting"
echo "2. Extract di hosting"
echo "3. Copy .env.production menjadi .env"
echo "4. Edit .env dengan kredensial database"
echo "5. Import database_export_*.sql"
echo "6. Test aplikasi"
echo ""
echo "Lihat DEPLOYMENT_CHECKLIST.md untuk panduan lengkap"
