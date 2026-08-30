# تغییرات نسخه‌ها

<div dir="rtl" markdown="1">

[🇬🇧 English](../en/changelog.md){ .language-switcher }

این صفحه تغییرات فعلی شاخه `main` را نگه می‌دارد. شماره نسخه‌ها و metadata منتشرشده از Packagist و Git tagها قابل مشاهده‌اند؛ برای tagهایی که Release Note معتبر داخل Repository ندارند، تاریخچه را حدس نمی‌زنیم.

## منتشرنشده (`main`)

### سازگاری و CI

- سازگاری اعلام‌شده و تست‌شده تا Laravel 13 و Orchestra Testbench 11 گسترش پیدا کرد.
- PHP syntax lint روی حداقل نسخه پشتیبانی‌شده یعنی PHP 8.1 اضافه شد.
- Matrix هشت‌تایی Laravel/PHP برای Laravel 10 تا 13 و ترکیب‌های PHP 8.1 تا 8.5 اضافه شد.
- GitHub Actionهای تست و مستندات به نسخه‌های جدید منتقل شدند.
- Build مستندات با `npm ci` انجام می‌شود و GitHub Pages با Actionهای فعلی deploy می‌شود.

### رفتار پکیج

- فایل‌های پکیج دیگر صرفاً با boot شدن Artisan داخل پروژه مصرف‌کننده کپی نمی‌شوند. Config، کلاس‌های پایه، Helper و Stubها فقط با `vendor:publish` صریح منتشر می‌شوند.
- باگ `Goli::diffForHumans()` در Carbonهای جدید اصلاح شد؛ مقادیر کسری `diffIn*()` دیگر واحد اشتباه و خروجی‌هایی مانند `0 سال` تولید نمی‌کنند.
- رفتار امن فایل‌های تولیدشده حفظ و تست شد: بدون `--force` فایل موجود skip می‌شود.
- حالت نبود Model بدون Schema یا Migration با integration test پوشش داده شد تا command پیش از تولید فایل ناقص fail شود.

### تست‌ها

- Integration Test با Orchestra Testbench برای ثبت commandها و publish groupهای پکیج اضافه شد.
- تستی اضافه شد که خود command واقعی `make:module` را در Laravel اجرا می‌کند و خروجی Repository/Service را بررسی می‌کند.
- قرارداد shortcutهای CLI تست شد تا `-a` همان `--all`، `-f` همان `--full` و گزینه‌های `--api` / `--force` بدون shortcut متداخل باقی بمانند.
- اسکریپت دستی Goli date cast به تست PHPUnit واقعی تبدیل شد.
- bootstrap و stubهای تست قدیمی حذف شدند.

### Repository و Distribution

- `vendor/`، cacheهای PHPUnit، فایل debug، `composer.lock` و دیتابیس generated مربوط به Nuxt Content از Git خارج شدند.
- `autoload-dev`، metadata بهتر Composer، `.gitattributes` و `.editorconfig` اضافه شدند.
- پوشه‌های توسعه‌ای مانند tests، docs، examples و workflowها با `export-ignore` از Composer distribution نهایی خارج می‌شوند.

### مستندات

- صفحات Installation، Quickstart، Configuration، CLI Reference، Swagger/OpenAPI، Public PHP API، Jalali، Usage Examples و Feature Map بر اساس سورس فعلی بازنویسی شدند.
- commandها، Facade/Serviceها، Carbon macroها، Factory/Migration/Seeder و Web UI خیالی یا منسوخ از مستندات حذف شدند.
- مثال Carbon macro شکسته با `examples/goli-date.php` واقعی بر پایه `Goli` و `goli()` جایگزین شد.
- صریح شد که Laravel Scaffolder لایه‌های اپلیکیشن را پیرامون Model/Schema موجود تولید می‌کند و خود Eloquent Model یا Migration را ایجاد نمی‌کند.

## نسخه‌های منتشرشده

Packagist در حال حاضر release line نسخه `8.x` شامل `v8.1.2`، `v8.1.1`، `v8.1.0` و tagهای `v8.0.x` را نمایش می‌دهد.

- Registry پکیج: https://packagist.org/packages/efati/laravel-scaffolder
- Tagهای Repository: https://github.com/AfshinEfati/Laravel-Scaffolder/tags

پیش از انتشار وضعیت فعلی `main` با نسخه جدید، diff دقیق آخرین tag منتشرشده با `main` باید مبنای Release Note قرار بگیرد.

</div>
