[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF)](https://php.net/)

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

## About us

At 123inkt (Part of Digital Revolution B.V.), every day more than 50 development professionals are working on improving our internal ERP 
and our several shops. Do you want to join us? [We are looking for developers](https://www.werkenbij123inkt.nl/zoek-op-afdeling/it).
