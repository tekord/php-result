# PHP Result

Result object for PHP inspired by the Rust programming language.

This object is useful when you want your errors to be returned instead of being thrown. Several helper functions make it
easy to use.

[![PHP Version Support][php-badge]][php]
[![Packagist version][packagist-badge]][packagist]
[![License][license-badge]][license]

[php-badge]: https://img.shields.io/packagist/php-v/tekord/php-result?logo=php&color=8892BF
[php]: https://www.php.net/supported-versions.php
[packagist-badge]: https://img.shields.io/packagist/v/tekord/php-result.svg?logo=packagist
[packagist]: https://packagist.org/packages/tekord/php-result
[license-badge]: https://img.shields.io/badge/license-MIT-green.svg
[license]: https://github.com/tekord/php-result/blob/main/LICENSE-MIT

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
        return Result::fail(
            new ErrorInfo("unverified_user", "Unverified users are not allowed to order", [
                'user' => $user
            ])
        );
        
    if ($user->hasDebts())
        return Result::fail(
            new ErrorInfo("user_has_debts", "Users with debts are not allowed to order new items", [
                'user' => $user
            ])
        );
        
    if ($products->isEmpty())
        return Result::fail(
            new ErrorInfo("no_products", "Products cannot be empty")
        );
  
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

You can extend the Result class to override the exception class (note the phpDoc):

```php
/**
 * @tempate OkType
 * @tempate ErrorType
 *
 * @extends Result<OkType, ErrorType>
 */
class CustomResult extends Result {
    static $panicExceptionClass = DomainException::class;
}
```

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email [cyrill@tekord.space](mailto:cyrill@tekord.space) instead of
using the issue tracker.
