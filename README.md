[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF)](https://php.net/)
![Run tests](https://github.com/123inkt/phpunit-extensions/actions/workflows/test.yml/badge.svg)

## PHPUnit extensions

Utility classes to make unit testing life easier.


## Features

### withConsecutive
In PHPUnit 10 withConsecutive method was removed. To still be able to migrate existing codebases a replacement method:

PHPUnit <= 9.5:
```php
$mock->method('myMethod')->withConsecutive([123, 'foobar'], [456]);
```
PHPUnit >= 9.6:
```php
$mock->method('myMethod')->with(...consecutive([123, 'foobar'], [456]));
```


### Symfony controller tests
Testing a Symfony controller internally invokes the dependency container. A utility class to mock these classes more easily.

```php
use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;

class MyControllerTest extends AbstractControllerTestCase 
{    
    public function myTest(): void 
    {
        $this->expectDenyAccessUnlessGranted('attribute', null, true);
        $this->expectGetUser(new User());
        $this->expectCreateForm(TextType::class);
        
        ($this->controller)();    
    }
    
    public function getController() {
        return new MyController();    
    }    
}
```

**Methods**
- `expectGetUser`
- `expectDenyAccessUnlessGranted`
- `expectCreateForm`
- `expectAddFlash`
- `expectGenerateUrl`
- `expectRedirectToRoute`
- `expectForward`
- `expectRender`

### Symfony ConstraintValidator tests

TestCase for testing Symfony ConstraintValidators.

```php
use DR\PHPUnitExtensions\Symfony\AbstractConstraintValidatorTestCase;

class MyConstraintValidatorTest extends AbstractConstraintValidatorTestCase 
{    
    public function testValidate(): void
    {
        $this->expectBuildViolation($constraint->message, ['parameter' => 123])
            ->expectSetCode(789)
            ->expectAtPath('path')
            ->expectAddViolation();

        $this->validator->validate(123, $this->constraint);
    }
    
    protected function getValidator(): ConstraintValidator
    {
        return new MyConstraintValidator();
    }

    protected function getConstraint(): Constraint
    {
        return new MyConstraint();
    }
}
```

**Methods**
- `assertHandlesIncorrectConstraintType`
- `expectNoViolations`
- `expectBuildViolation(): ConstraintViolationBuilderAssertion`

**ConstraintViolationBuilderAssertion**
- `expectSetInvalidValue`
- `expectSetPlural`
- `expectSetCode`
- `expectSetCause`
- `expectSetTranslationDomain`
- `expectSetParameters`
- `expectSetParameter`
- `expectSetParameterWithConsecutive`
- `expectAtPath`
- `expectAddViolation`

### ResponseAssertions trait
The ResponseAssertions trait provides a set of assertion methods designed to streamline the testing of Symfony HTTP responses. 
This trait includes methods for verifying the status code, response message content and specific types of responses such as JSON responses

**Methods**
- `assertJsonResponse`
- `assertResponse`
- `assertResponseIsSuccessful`
- `assertResponseIsRedirection`
- `assertResponseIsClientError`
- `assertResponseIsServerError`

### ClockTestTrait
The ClockTestTrait provides a set of methods to manipulate the current time in tests. This trait will automatically freeze the time at the start of
each test. The trait also provides methods to get the current time as timestamp or `DateTimeImmutable` object, 

**Methods**
- `self::time(): int`
- `self::now(): DateTimeImmutable`
- `self::sleep(int|float $seconds): void`
- `self::assertNow()`
- `self::assertSameTime()`

### ImageTestTrait
The `ImageTestTrait` provides methods to compare to images by binary string, `SplFileInfo` or resource. (Requires Imagick)

- `self::assertSameImage(string|SplFileInfo|resource, string|SplFileInfo|resource)`
- `self::assertNotSameImage(string|SplFileInfo|resource, string|SplFileInfo|resource)`

## About us

At 123inkt (Part of Digital Revolution B.V.), every day more than 50 development professionals are working on improving our internal ERP 
and our several shops. Do you want to join us? [We are looking for developers](https://www.werkenbij123inkt.nl/zoek-op-afdeling/it).
