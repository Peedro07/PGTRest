# PGTRest

## Bundle to simplify Symfony API creation

*Version 2.0. of the bundle works with Symfony 7.x.x*

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
//UserEntity
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[Groups(["user:read"])] // Initialize the group for serialization
#[ORM\Column(length: 255)]
private ?string $name = null;
```

```php
//UserController
use App\Repository\UserRepository;
use PGTRest\Attribute\ResponseOptions;
use PGTRest\Controller\AbstractPGTRest;
use PGTRest\Service\ResponseOptionsService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

    public function __construct(ResponseOptionsService $responseOptionsService)
    {
        parent::__construct($responseOptionsService);  
    }

    #[Route('/users', name: 'app_users_get', methods: ["GET"])]
    #[ResponseOptions(statusCode: 200, groups: ["user:read"], formatDate: 'Y-m-d']) // Set the response options with the desired status code and serialization group
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
    
        return $this->view($users); //option 1
        //return $this->view(['all_users' => $users]); option 2
    }
```

```json
//option 1
{
    "users": [
        {
            "name": "John"
        },
        {
            "name": "Jane"
        }
    ]
}
```

```json

//option 2
{
    "all_users": [
        {
            "name": "John"
        },
        {
            "name": "Jane"
        }
    ]
}
```

Note: The view method accepts an array of data and optional parameters for $statusCode and $groups

### Additional Features
When the `formatDate` option is set, the bundle will format the date fields in the response according to the specified format.
```php
#[ResponseOptions(statusCode: 200, groups: ["user:read"], formatDate: 'Y-m-d']) 
```


