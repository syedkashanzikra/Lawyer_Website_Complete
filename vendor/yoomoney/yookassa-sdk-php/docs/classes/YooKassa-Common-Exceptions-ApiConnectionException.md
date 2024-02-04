# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Common\Exceptions\ApiConnectionException
### Namespace: [\YooKassa\Common\Exceptions](../namespaces/yookassa-common-exceptions.md)
---
**Summary:**

Неожиданный код ошибки.


---
### Constants
* No constants found

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| protected | [$responseBody](../classes/YooKassa-Common-Exceptions-ApiException.md#property_responseBody) |  |  |
| protected | [$responseHeaders](../classes/YooKassa-Common-Exceptions-ApiException.md#property_responseHeaders) |  |  |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [__construct()](../classes/YooKassa-Common-Exceptions-ApiException.md#method___construct) |  | Constructor. |
| public | [getResponseBody()](../classes/YooKassa-Common-Exceptions-ApiException.md#method_getResponseBody) |  |  |
| public | [getResponseHeaders()](../classes/YooKassa-Common-Exceptions-ApiException.md#method_getResponseHeaders) |  |  |

---
### Details
* File: [lib/Common/Exceptions/ApiConnectionException.php](../../lib/Common/Exceptions/ApiConnectionException.php)
* Package: Default
* Class Hierarchy:  
  * [\Exception](\Exception)
  * [\YooKassa\Common\Exceptions\ApiException](../classes/YooKassa-Common-Exceptions-ApiException.md)
  * \YooKassa\Common\Exceptions\ApiConnectionException

---
## Properties
<a name="property_responseBody"></a>
#### protected $responseBody : ?string
---
**Type:** <a href="../?string"><abbr title="?string">?string</abbr></a>

**Details:**
* Inherited From: [\YooKassa\Common\Exceptions\ApiException](../classes/YooKassa-Common-Exceptions-ApiException.md)


<a name="property_responseHeaders"></a>
#### protected $responseHeaders : array
---
**Type:** <a href="../array"><abbr title="array">array</abbr></a>

**Details:**
* Inherited From: [\YooKassa\Common\Exceptions\ApiException](../classes/YooKassa-Common-Exceptions-ApiException.md)



---
## Methods
<a name="method___construct" class="anchor"></a>
#### public __construct() : mixed

```php
public __construct(string $message = &#039;&#039;, int $code, string[] $responseHeaders = [], string|null $responseBody = &#039;&#039;) : mixed
```

**Summary**

Constructor.

**Details:**
* Inherited From: [\YooKassa\Common\Exceptions\ApiException](../classes/YooKassa-Common-Exceptions-ApiException.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | message  | Error message |
| <code lang="php">int</code> | code  | HTTP status code |
| <code lang="php">string[]</code> | responseHeaders  | HTTP header |
| <code lang="php">string OR null</code> | responseBody  | HTTP body |

**Returns:** mixed - 


<a name="method_getResponseBody" class="anchor"></a>
#### public getResponseBody() : ?string

```php
public getResponseBody() : ?string
```

**Details:**
* Inherited From: [\YooKassa\Common\Exceptions\ApiException](../classes/YooKassa-Common-Exceptions-ApiException.md)

**Returns:** ?string - 


<a name="method_getResponseHeaders" class="anchor"></a>
#### public getResponseHeaders() : string[]

```php
public getResponseHeaders() : string[]
```

**Details:**
* Inherited From: [\YooKassa\Common\Exceptions\ApiException](../classes/YooKassa-Common-Exceptions-ApiException.md)

**Returns:** string[] - 



---

### Top Namespaces

* [\YooKassa](../namespaces/yookassa.md)

---

### Reports
* [Errors - 0](../reports/errors.md)
* [Markers - 0](../reports/markers.md)
* [Deprecated - 13](../reports/deprecated.md)

---

This document was automatically generated from source code comments on 2023-06-30 using [phpDocumentor](http://www.phpdoc.org/)

&copy; 2023 YooMoney