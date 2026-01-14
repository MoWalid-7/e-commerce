# 🛒 Advanced E-commerce API (Laravel)

هذا المشروع عبارة عن Restful API متكامل لإدارة متجر إلكتروني.

### ✨ المميزات (Features):
- **Authentication:** باستخدام Laravel Sanctum.
- **Product Management:** إضافة، تعديل، وحذف المنتجات مع نظام Categories.
- **Order System:** نظام سلة المشتريات وتحويلها إلى طلبات مؤكدة.
- **Database:** علاقات معقدة (One-to-Many, Many-to-Many) بين المنتجات والطلبات والمستخدمين.

### 🛠 التقنيات المستخدمة:
- PHP 8.x & Laravel 10
- MySQL (Database)
- Eloquent ORM & Migrations
- Validation (Form Requests)

### 📊 Database Schema:
[إرسم هنا شكل الجداول أو حط صورة للـ ER Diagram]

### 🚀 كيف تشغل المشروع؟
1. `composer install`
2. `php artisan migrate --seed`
3. `php artisan serve`