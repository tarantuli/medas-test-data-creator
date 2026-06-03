# medas-test-data-creator

Part of the [Medas framework](https://github.com/tarantuli/medas-core).

## Description

A YAML-driven test data generator backed by `fakerphp/faker` and `medas-entity-manager`. Given a directory containing an `index.yaml` file, `YamlProcessor` reads action and definition blocks, generates entity property values (using Faker formatters, random functions, or context references), creates entities via the entity manager, and flushes them to the configured storage backend.

**YAML structure:**

An `index.yaml` file contains two top-level keys: `definitions` (reusable entity templates) and `actions` (ordered list of create/clear instructions).

**Supported action types:**

| Type     | Effect                                                 |
|----------|--------------------------------------------------------|
| `create` | Creates N entities from a named definition             |
| `clear`  | Deletes all instances of all registered entity classes |

**Value processors — functions available in property values:**

| Function           | Syntax                                                          | Returns                                                       |
|--------------------|-----------------------------------------------------------------|---------------------------------------------------------------|
| Faker formatter    | `faker(name)` / `faker(name, arg)`                              | Calls `$faker->name()` with optional argument                 |
| `between`          | `between(min, max)`                                             | Random integer in range                                       |
| `between` (biased) | `between(min, max, low-bias)` or `between(min, max, high-bias)` | Biased random integer                                         |
| `chance`           | `chance(pct, then, else?)`                                      | Returns `then` with `pct`% probability, else `else` or `null` |
| `if`               | `if(condition, then, else)`                                     | Ternary — returns `then` if condition is truthy               |
| `filter`           | `filter(Entity/Class, prop, val, ...)`                          | Returns fetched entities matching filters                     |
| `future_date`      | `future_date(days?)`                                            | A `DateTime` up to N days in the future                       |
| `create`           | `create(DefinitionName)`                                        | Recursively creates and returns a nested entity               |
| `switch`           | `switch(value, case1, result1, ...)`                            | Returns the result matching the value                         |
| Context reference  | `$variableName`                                                 | Value of a named context variable from the current scope      |

Scalar coercion: `true`, `false`, `null`, and numeric strings are converted to their PHP equivalents.

**YAML imports** — split large fixtures across multiple files using `import(relative/path.yaml)`. Circular imports are detected and throw `CircularImportDetected`. Paths that escape the job directory throw `ImportPathEscapesJobDirectory`.

## Usage

### Package developer context

Register the package and inject `YamlProcessor`:

```php
use Medas\TestDataCreator\TestDataCreatorPackage;

TestDataCreatorPackage::instance();
```

**Processing a fixtures directory:**

```php
use Medas\TestDataCreator\YamlProcessor;
use Medas\Core\Attributes\Service;

#[Service]
readonly class DataSeeder
{
    public function __construct(
        private YamlProcessor $processor,
    ) {}

    public function seed(string $fixturesDirectory): void
    {
        $this->processor->process($fixturesDirectory);
    }
}
```

**Example `index.yaml`:**

```yaml
locale: nl_NL

definitions:
  user:
    entity: App\Entities\User
    properties:
      name: faker(name)
      email: faker(email)
      age: between(18, 65)
      role: chance(20, admin, member)

  post:
    entity: App\Entities\Post
    properties:
      title: faker(sentence, 6)
      body: faker(paragraphs, 3)
      publishedAt: future_date(30)
      author: create(user)

actions:
  - type: clear
    entities: all

  - type: create
    definition: user
    count: 50

  - type: create
    definition: post
    count: between(100, 200)
```

**Nested entities with children:**

```yaml
definitions:
  department:
    entity: App\Entities\Department
    properties:
      name: faker(company)
    children:
      - type: create
        definition: employee
        count: between(3, 10)
        properties:
          department: $parent

  employee:
    entity: App\Entities\Employee
    properties:
      name: faker(name)
      email: faker(email)
```

Children are created in a loop after the parent entity is created. The parent is available as `$parent` in the child's context.

**YAML imports — splitting fixtures into files:**

```yaml
# index.yaml
locale: en_GB

definitions:
  import(definitions/users.yaml)
  import(definitions/products.yaml)

actions:
  import(actions/seed.yaml)
```

Indentation of the `import()` call is preserved when the content is inlined.

**Using `filter()` to reference existing entities:**

```yaml
definitions:
  review:
    entity: App\Entities\Review
    properties:
      product: filter(App/Entities/Product, status, active)
      rating: between(1, 5)
      body: faker(paragraph)
```

`filter()` flushes pending creates first, then fetches matching entities, and returns the array. Use with `chance()` or array indexing to pick a specific one.

**Using `switch()` for conditional values:**

```yaml
definitions:
  order:
    entity: App\Entities\Order
    properties:
      status: chance(70, completed, pending)
      shippedAt: switch($status, completed, future_date(0), null)
```

### Backend user context

**Progress output** — set `printProgress: true` at the top of `index.yaml` to enable console progress lines during seeding:

```yaml
printProgress: true
locale: en_US

definitions:
  # ...
```

Each create/clear action will print its progress and a "done" confirmation to stdout.

**Faker locale** — set the `locale` key to any Faker-supported locale string (e.g. `nl_NL`, `en_US`, `de_DE`). This affects all locale-sensitive formatters like `name`, `address`, `phoneNumber`, etc.

**Entity clearing** — `type: clear` with `entities: all` deletes every entity registered in the entity manager, one class at a time, and flushes after each. Run it at the start of your `actions` list to ensure a clean slate before seeding. `entities: none` is a no-op (useful for conditional seeding scripts).
