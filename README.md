# PHP Result

Result object for PHP inspired by the Rust programming language.

This object is useful when you want your errors to be returned instead of being thrown. A couple of helper methods makes
the usage very smooth.

## Installation

Install the package via Composer:

```bash
composer require tekord/php-result
```

## Usage

Example:

```php
class ErrorInfo {
    public $code;
    public $message;
    public $context;

    public function __construct($code, $message, $context = []) {
        $this->code = $code;
        $this->message = $message;
        $this->context = $context;
    }
}

function createOrder(User $user, $products): Result {
    if (!$user->isVerified())
        return Result::fail(new ErrorInfo("unverified_user", "Unverified users are not allowed to order", [
            'user' => $user
        ]);
        
    if ($user->hasDebts())
        return Result::fail(new ErrorInfo("user_has_debts", "Users with debts are not allowed to order new items", [
            'user' => $user
        ]);
        
    if ($products->isEmpty())
        return Result::fail(new ErrorInfo("no_products", "Products cannot be empty");
  
    // Create a new order here...
    
    return Result::success($newOrder);
}

// ...

$createOrderResult = createOrder($user, $productsFromCart);

// This will throw a panic exception if the result contains an error
$newOrder = $createOrderResult->unwrap();
   
// - OR -

// You can check if the result is OK and make a decision on it
if ($createOrderResult->isOk()) {
    $newOrder = $createOrderResult->ok;
    
    // ...
}
else {
    throw new DomainException($createOrderResult->error->message);
}

// - OR -

// Get a default value if the result contains an error
$newOrder = $createOrderResult->unwrapOrDefault(new Order());
```

You can customize a panic exception throwing:

```php
Result::$panicCallback = function ($error) {
    if ($error instanceof ErrorInfo)
        throw new DomainException("[$error->code] " . $error->message);
        
   if ($error instanceof Exception)
        throw $error;
        
    throw new Exception((string)$error);
};

$o = Result::fail(new ErrorInfo("no_products", "Products cannot be empty"));

// Throws a DomainException with the message: "[no_products] Products cannot be empty"
$o->unwrap();
```

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email [cyrill@tekord.space](mailto:cyrill@tekord.space) instead of
using the issue tracker.
