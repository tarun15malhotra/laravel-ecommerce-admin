## 🛍️ Laravel Ecommerce Admin Panel

A comprehensive, feature-rich ecommerce administration panel built with Laravel 11+, designed for managing online stores with powerful analytics, inventory management, and customer relationship tools.

## ✨ Features

- **Dashboard Analytics**: Real-time sales metrics, charts, and performance indicators
- **Product Management**: Complete CRUD operations with categories, tags, SKU, and stock tracking
- **Order Management**: Order processing, status tracking, invoicing, and shipping management
- **Customer Management**: Customer profiles, order history, segmentation, and analytics
- **Inventory Control**: Stock management, low stock alerts, and bulk operations
- **Coupon System**: Flexible discount rules, usage limits, and expiry management
- **Reports & Analytics**: Sales reports, product performance, customer analytics
- **Role-Based Access**: Super Admin, Manager, and Staff roles with permissions
- **Import/Export**: Bulk product import/export with Excel support
- **Invoice Generation**: PDF invoice generation and download
- **Settings Management**: Store configuration, payment methods, shipping options

## 🛠️ Technology Stack

- **Backend**: Laravel 11+, PHP 8.2
- **Database**: MySQL/PostgreSQL
- **Frontend**: Blade Templates, TailwindCSS, Alpine.js
- **Authentication**: Laravel Breeze/Jetstream
- **Authorization**: Spatie Laravel Permission
- **File Handling**: Maatwebsite Excel, BarryVDH DomPDF
- **Image Processing**: Intervention Image
- **Development**: Laravel Sail (Docker)
- **Testing**: PHPUnit, Pest

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0+ or PostgreSQL 13+
- Node.js 18+ and npm
- Redis (optional, for caching)
- Docker (optional, for Laravel Sail)

## 🚀 Installation

### 1. Clone the repository
```bash
cd /home/spxlpt171/CascadeProjects/ecommerce-admin
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Install JavaScript dependencies
```bash
npm install
```

### 4. Configure environment
Copy the `.env.example` file and configure your database:
```bash
cp .env.example .env
```

Edit the `.env` file:
```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_admin
DB_USERNAME=laravel
DB_PASSWORD=your_password

# Application URL
APP_URL=http://localhost:8000

# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### 5. Generate application key
```bash
php artisan key:generate
```

### 6. Create database and run migrations
```bash
php artisan migrate
```

### 7. Seed the database with sample data
```bash
php artisan db:seed
```

### 8. Build frontend assets
```bash
npm run build
# Or for development with hot reload:
npm run dev
```

### 9. Create storage link
```bash
php artisan storage:link
```

### 10. Start the development server
```bash
php artisan serve
# Or use Laravel Sail (Docker):
./vendor/bin/sail up
```

### 11. (Optional) Run Tests
```bash
php artisan test
# Or with coverage:
php artisan test --coverage
```

## 📊 Sample Data

The application comes with comprehensive seeders that create realistic sample data:

### 📦 Seeded Data Includes:
- **62 Categories** (hierarchical structure with parent-child relationships)
- **135 Products** (with images, variants, and pricing)
- **75 Customers** (with order history and preferences)
- **175 Orders** (various statuses and payment methods)
- **23 Coupons** (active and expired with usage limits)
- **25 Tags** (for product categorization)
- **10 Admin Users** (with different roles)
- **52 Settings** (store configuration)

### 👤 Default Admin Credentials:
| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| Super Admin | admin@example.com | password | Full system access |
| Manager | manager@example.com | password | Manage products, orders, customers |
| Staff | staff@example.com | password | View and process orders |

### 🎯 Test User for Customer Portal:
| Type | Email | Password | Purpose |
|------|-------|----------|---------|
| Customer | customer@example.com | password | Test customer features |

## 📚 Usage

### 1. Admin Dashboard
- Login at `/login` with admin credentials
- Access dashboard at `/admin/dashboard`
- View real-time sales metrics and analytics

### 2. Product Management
- Navigate to **Products** → **All Products**
- Create new products with images, categories, and variants
- Manage inventory levels and track stock
- Bulk import/export products via Excel

### 3. Order Processing
- View all orders in **Orders** → **All Orders**
- Update order status (Pending → Processing → Shipped → Delivered)
- Generate and download invoices as PDF
- Manage shipping and tracking information

### 4. Customer Management
- Access customer profiles and order history
- View customer analytics and lifetime value
- Segment customers by purchase behavior
- Export customer data for marketing

### 5. Reports & Analytics
- **Sales Report**: Revenue trends, order statistics
- **Product Performance**: Best sellers, inventory turnover
- **Customer Analytics**: Acquisition, retention, segments

## 🎨 Available Modules

### Core Modules:
- **Dashboard**: Overview cards, sales charts, recent activities
- **Products**: CRUD, categories, tags, images, inventory
- **Orders**: Processing, status updates, invoicing
- **Customers**: Profiles, groups, preferences, history
- **Categories**: Hierarchical structure with parent-child
- **Coupons**: Discount rules, usage tracking, expiry

### Analytics Modules:
- **Sales Reports**: Daily/monthly/yearly revenue analysis
- **Product Reports**: Performance metrics, stock analysis
- **Customer Reports**: Segmentation, lifetime value, retention

### Configuration Modules:
- **Settings**: Store details, tax, shipping zones
- **Payment Methods**: COD, PayPal, Stripe integration
- **Shipping Methods**: Flat rate, free shipping, carrier APIs
- **Email Templates**: Order confirmation, shipping notifications

## 🔒 Security & Permissions

### Role Hierarchy:
- **SUPER_ADMIN**: Complete system control
- **MANAGER**: Manage products, orders, customers
- **STAFF**: Process orders, view reports

### Permission Groups:
- Product management (create, edit, delete, export)
- Order management (view, update status, invoice)
- Customer management (view, edit, export)
- Report access (sales, products, customers)
- Settings management (store, payment, shipping)

## 📦 Project Structure

```
ecommerce-admin/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/          # Admin panel controllers
│   │   └── Middleware/
│   ├── Models/                 # Eloquent models
│   ├── Exports/                # Excel export classes
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database migrations
│   ├── factories/              # Model factories
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin panel views
│   │   ├── layouts/            # Layout templates
│   │   └── components/         # Reusable components
│   ├── js/                     # JavaScript files
│   └── css/                    # Stylesheets
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes
├── tests/
│   ├── Feature/                # Feature tests
│   └── Unit/                   # Unit tests
└── public/                     # Public assets
```

## 🚧 Troubleshooting

### Database Connection Issues
- Ensure MySQL/PostgreSQL is running
- Check credentials in `.env` file
- Run `php artisan config:clear`

### Missing Styles or JavaScript
- Run `npm install` to install dependencies
- Build assets with `npm run build`
- Clear cache: `php artisan cache:clear`

### Storage Permission Issues
- Set proper permissions: `chmod -R 775 storage bootstrap/cache`
- Create storage link: `php artisan storage:link`

### Class Not Found Errors
- Run `composer dump-autoload`
- Clear all caches: `php artisan optimize:clear`

## 🔄 Maintenance Commands

```bash
# Clear all caches
php artisan optimize:clear

# Refresh database with seeders
php artisan migrate:fresh --seed

# Run scheduled tasks
php artisan schedule:work

# Process queued jobs
php artisan queue:work

# Generate IDE helper files
php artisan ide-helper:generate
```

## 📤 Import/Export Features

### Product Import:
1. Download template from Products → Import
2. Fill in product data in Excel
3. Upload and process import

### Customer Export:
1. Go to Customers → Export
2. Select format (CSV/Excel)
3. Choose fields to export
4. Download file

## 🎯 Roadmap

- [ ] Multi-vendor marketplace support
- [ ] Advanced inventory management with warehouses
- [ ] AI-powered product recommendations
- [ ] Mobile app for order management
- [ ] Real-time chat support system
- [ ] Advanced shipping integrations (FedEx, UPS, DHL)
- [ ] Subscription and recurring payments
- [ ] Abandoned cart recovery
- [ ] Multi-language support
- [ ] PWA version for mobile

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage report
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/ProductTest.php
```

### Test Coverage:
- ✅ Authentication flows
- ✅ Product CRUD operations
- ✅ Order processing workflow
- ✅ Customer management
- ✅ Permission and role checks

## 📝 API Documentation

The admin panel includes RESTful APIs for integration:

### Endpoints:
- `GET /api/products` - List products
- `GET /api/orders` - List orders
- `GET /api/customers` - List customers
- `POST /api/orders/{id}/status` - Update order status

Authentication via Laravel Sanctum tokens.

## 🐳 Docker Setup (Laravel Sail)

```bash
# Start containers
./vendor/bin/sail up -d

# Stop containers
./vendor/bin/sail down

# Run artisan commands
./vendor/bin/sail artisan migrate

# Access MySQL
./vendor/bin/sail mysql
```

## 📈 Performance Optimization

1. **Caching**: Redis for session and cache storage
2. **Queue**: Process heavy tasks asynchronously
3. **Indexing**: Database indexes on frequently queried columns
4. **Eager Loading**: Prevent N+1 queries
5. **CDN**: Serve static assets via CDN
6. **Compression**: Gzip compression for responses

## 💡 Tips & Best Practices

1. **Backups**: Regular database and file backups
2. **Monitoring**: Set up error tracking (Sentry, Bugsnag)
3. **Security**: Keep dependencies updated
4. **Performance**: Use caching strategically
5. **Testing**: Write tests for critical features
6. **Documentation**: Keep API documentation updated

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a pull request

## 📄 License

This project is proprietary software for demonstration purposes.

## 🆘 Support

For issues, questions, or feature requests:
- Check the documentation
- Search existing issues
- Contact the development team

## 🙏 Acknowledgments

- Laravel Framework
- Spatie for excellent packages
- TailwindCSS for the UI framework
- All contributors and testers

---

**Built with ❤️ using Laravel 11+ and TailwindCSS** Sponsors

