# PHP Result

Result object for PHP inspired by the Rust programming language.

This object is useful when you want your errors to be returned instead of being thrown. A couple of helper methods makes the usage very smooth.

## Installation

Install the package via Composer:

```bash
composer require tekord/php-result
```

## Usage

Example:

```php
function createOrder(User $user, $products) {
    if (!$user->isVerified())
        return Result::fail("Unverified user");
        
    if ($user->hasDebts())
        return Result::fail("Users with debts are not allowed to order new items");
        
    if ($products->isEmpty())
        return Result::fail("Products cannot be empty");
  
    // Create a new order here...
    
    return Result::success($newOrder);
}

...

$createOrderResult = createOrder($user, $productsFromCart);

// This will throw an exception if the result contains an error
$newOrder = $createOrderResult->unwrap();
   
// - OR -

// You can check if the result is OK and make a decision on it
if ($createOrderResult->isOk()) {
    $newOrder = $createOrderResult->ok;
    
    // ...
}
else {
    throw new DomainException($createOrderResult->error);
}

// - OR -

// Get a default value if the result contains an error
$newOrder = $createOrderResult->unwrapOrDefault(new Order());
```

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email [cyrill@tekord.space](mailto:cyrill@tekord.space) instead of
using the issue tracker.
