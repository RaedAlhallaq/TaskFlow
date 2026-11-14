# TaskFlow — Laravel App

تطبيق Laravel يحتوي على جزأين:

- `/tasks`: إدارة مهام تفاعلية بـ Livewire (إضافة، تعديل، حذف، تغيير الحالة، بحث وفلترة) دون إعادة تحميل الصفحة.
- `/books`: واجهة تقرأ وتضيف وتحذف كتباً من خادم Go عبر HTTP Client وتبادل JSON.

## التشغيل

```bash
composer install
php artisan key:generate
php artisan serve
```

ثم افتح `http://127.0.0.1:8000`.

شغّل خادم Go أولاً من المجلد المجاور:

```bash
cd ../go-backend
go run .
```

## الإعدادات

- `GO_API_URL=http://127.0.0.1:8080` في ملف `.env`
- قاعدة البيانات: SQLite في `database/database.sqlite`

لإعادة إنشاء البيانات التجريبية:

```bash
php artisan migrate:fresh --seed
```

## الاختبارات

```bash
php artisan test
```
