# METRO Markets Pricing Aggregator (Laravel)

## 🛠 Setup Instructions

```bash
git clone https://github.com/your-username/metro-pricing.git
cd metro-pricing
composer install
cp .env.example .env
php artisan key:generate
# Configure DB credentials + API_KEY in .env
php artisan migrate
php artisan prices:fetch
php artisan serve

# Product list Api
curl -H "API-Key: secretKey" http://localhost:8000/api/prices

# Product details Api
curl -H "API-Key: secretKey" http://localhost:8000/api/prices/123