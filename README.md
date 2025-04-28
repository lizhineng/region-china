# China region library for PHP

This library helps you manage regional data for mainland China, updated
annually based on information from the _Ministry of Civil Affairs of
the People's Republic of China_. The latest update was applied on
[April 25, 2025](https://www.mca.gov.cn/mzsj/xzqh/2025/202401xzqh.html).

## Usage

The library includes built-in data sourced directly from the authority,
so you don't need to maintain it yourself. You can find the update script
in the _scripts_ folder. First things first, let's initialize an instance
of this helpful library.

```php
use Zhineng\RegionChina\RegionManager;

$regions = RegionManager::createFromBuiltIn();
```

### Resolve the region name from the code

Region codes are good for database persistence, but not very helpful for
humans. If you have the region code, you can easily convert it back to the
Chinese name.

```php
$regions->getName($beijing = 110000); // 北京市
```

### Retrieve provinces

When creating an interactive multi-level selector for choosing provinces,
cities, and districts, it's helpful to first list all provinces in China,
allowing you to display the corresponding cities and districts based on
the user's selection.

```php
$regions->getTopLevelNodes(); // [110000, 120000, 130000, ...]
```

### Retrieve cities or districts

For example, if you want to list all cities in Guangdong Province or all
districts in Guangzhou City, you only need a single API call to retrieve
all sub-region codes for the specified area. You can then use these codes
to obtain the corresponding names.

```php
// Retrieve city codes for the Guangdong province
$regions->getNodes($guangdong = 440000);

// Retrieve district codes for the Guangzhou city
$regions->getNodes($guangzhou = 440100);
```
