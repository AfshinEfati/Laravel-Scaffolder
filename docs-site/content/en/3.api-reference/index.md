---
title: Repository and Service API
---

# Repository and Service API

The generated architecture follows a clean separation of concerns using Repositories and Services.

## Repository API

Generated repositories extend `BaseRepository` and provide several core methods:

- `getAll()`: Returns all records.
- `find($id)`: Find a single model by ID.
- `store($data)`: Create a new record.
- `update($id, $data)`: Update an existing record.
- `delete($id)`: Remove a record.

### Dynamic Queries

You can use `findDynamic` or `getByDynamic` for complex filtering:

```php
$products = $productRepository->getByDynamic(
    where: [['status', '=', 'active']],
    with: ['category'],
    whereIn: ['category_id', [1, 2, 3]],
);
```

## Service API

Services wrap the repository and provide a high-level API for your controllers.

```php
use App\Services\ProductService;

public function index(ProductService $service)
{
    return $service->index();
}
```

### DTO Integration

When DTOs are enabled, the service methods automatically handle them:

```php
$service->store(ProductDTO::fromRequest($request));
```
