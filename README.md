# PGTRest

## Bundle for Simplified Symfony API Creation

This bundle streamlines API development using convenient annotations. It requires the extension of `AbstractPGTRest`.

### Getting Started

To begin, extend the bundle in your controller:

```php
class UserController extends AbstractPGTRest
```

Ensure the `responseOptionsService` is initialized by invoking the parent constructor:

```php
parent::__construct($responseOptionsService);
```

### Example Usage

Utilize the bundle through an illustrative example:

```php
#[Route('/users', name: 'app_admin_users_get', methods: ["GET"])]
#[ResponseOptions(statusCode: 200, groups: ["user:read"])]
public function index(UserRepository $userRepository): JsonResponse
{
    $users = $userRepository->findAll();

    return $this->view($users);
}
```

Note: The view method accepts an array of data and optional parameters for $statusCode and $groups