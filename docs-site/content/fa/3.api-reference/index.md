---
title: لایه Repository و Service
---

# لایه Repository و Service

معماری تولید شده توسط این پکیج از الگوی Repository و Service برای جداسازی مسئولیت‌ها استفاده می‌کند.

## لایه Repository

ریپازیتوری‌های تولید شده از `BaseRepository` ارث‌بری می‌کنند و متدهای زیر را در اختیار شما قرار می‌دهند:

- `getAll()`: دریافت تمام رکوردها.
- `find($id)`: پیدا کردن یک رکورد با شناسه.
- `store($data)`: ایجاد رکورد جدید.
- `update($id, $data)`: بروزرسانی رکورد موجود.
- `delete($id)`: حذف رکورد.

### کوئری‌های پویا (Dynamic Queries)

برای فیلترهای پیچیده می‌توانید از `getByDynamic` استفاده کنید:

```php
$products = $productRepository->getByDynamic(
    where: [['status', '=', 'active']],
    with: ['category'],
    whereIn: ['category_id', [1, 2, 3]],
);
```

## لایه Service

سرویس‌ها به عنوان یک واسط بین Controller و Repository عمل کرده و منطق برنامه را در خود جای می‌دهند.

```php
use App\Services\ProductService;

public function index(ProductService $service)
{
    // سرویس به صورت خودکار از ریپازیتوری استفاده می‌کند
    return $service->index();
}
```

### استفاده از DTO

اگر لایه DTO فعال باشد، متدهای سرویس به صورت هوشمند از آن استفاده می‌کنند:

```php
$service->store(ProductDTO::fromRequest($request));
```
