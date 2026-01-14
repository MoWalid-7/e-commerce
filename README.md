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
- Eloquent ORM & Migrations![Uploading Untitled.svg…]()

- Validation (Form Requests)

### 📊 Database Schema:
[img width="1537" height="890" alt="Untitled" src="https://github.com/user-attachments/assets/bafb8c01-06d4-40eb-bcea-0155cc0d9318" />


### 🚀 كيف تشغل المشروع؟
1. `composer install`
2. `php artisan migrate --seed`
3. `php artisan serve`
