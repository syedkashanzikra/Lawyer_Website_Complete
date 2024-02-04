# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Request\PersonalData\CreatePersonalDataRequest
### Namespace: [\YooKassa\Request\PersonalData](../namespaces/yookassa-request-personaldata.md)
---
**Summary:**

Класс, представляющий модель CreatePersonalDataRequest.


---
### Examples
Пример использования билдера

```php
try {
    $personalDataBuilder = \YooKassa\Request\PersonalData\CreatePersonalDataRequest::builder();
    $personalDataBuilder
        ->setType(\YooKassa\Model\PersonalData\PersonalDataType::SBP_PAYOUT_RECIPIENT)
        ->setFirstName('Иван')
        ->setLastName('Иванов')
        ->setMiddleName('Иванович')
        ->setMetadata(['recipient_id' => '37'])
    ;

    // Создаем объект запроса
    $request = $personalDataBuilder->build();

    $idempotenceKey = uniqid('', true);
    $response = $client->createPersonalData($request, $idempotenceKey);
} catch (Exception $e) {
    $response = $e;
}

var_dump($response);

```

---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [MAX_LENGTH_LAST_NAME](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#constant_MAX_LENGTH_LAST_NAME) |  | Максимальная длина строки фамилии или отчества |
| public | [MAX_LENGTH_FIRST_NAME](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#constant_MAX_LENGTH_FIRST_NAME) |  | Максимальная длина строки имени |

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [$first_name](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_first_name) |  | Имя пользователя |
| public | [$firstName](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_firstName) |  | Имя пользователя |
| public | [$last_name](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_last_name) |  | Фамилия пользователя |
| public | [$lastName](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_lastName) |  | Фамилия пользователя |
| public | [$metadata](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_metadata) |  | Метаданные персональных данных указанные мерчантом |
| public | [$middle_name](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_middle_name) |  | Отчество пользователя |
| public | [$middleName](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_middleName) |  | Отчество пользователя |
| public | [$type](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property_type) |  | Тип персональных данных |
| protected | [$_metadata](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#property__metadata) |  | Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа). |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [__construct()](../classes/YooKassa-Common-AbstractObject.md#method___construct) |  | AbstractObject constructor. |
| public | [__get()](../classes/YooKassa-Common-AbstractObject.md#method___get) |  | Возвращает значение свойства. |
| public | [__isset()](../classes/YooKassa-Common-AbstractObject.md#method___isset) |  | Проверяет наличие свойства. |
| public | [__set()](../classes/YooKassa-Common-AbstractObject.md#method___set) |  | Устанавливает значение свойства. |
| public | [__unset()](../classes/YooKassa-Common-AbstractObject.md#method___unset) |  | Удаляет свойство. |
| public | [builder()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_builder) |  | Возвращает билдер объектов запросов создания платежа. |
| public | [clearValidationError()](../classes/YooKassa-Common-AbstractRequest.md#method_clearValidationError) |  | Очищает статус валидации текущего запроса. |
| public | [fromArray()](../classes/YooKassa-Common-AbstractObject.md#method_fromArray) |  | Устанавливает значения свойств текущего объекта из массива. |
| public | [getFirstName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_getFirstName) |  | Возвращает имя пользователя. |
| public | [getLastName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_getLastName) |  | Возвращает фамилию пользователя. |
| public | [getLastValidationError()](../classes/YooKassa-Common-AbstractRequest.md#method_getLastValidationError) |  | Возвращает последнюю ошибку валидации. |
| public | [getMetadata()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_getMetadata) |  | Возвращает любые дополнительные данные. |
| public | [getMiddleName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_getMiddleName) |  | Возвращает отчество пользователя. |
| public | [getType()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_getType) |  | Возвращает тип персональных данных. |
| public | [getValidator()](../classes/YooKassa-Common-AbstractObject.md#method_getValidator) |  |  |
| public | [hasFirstName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_hasFirstName) |  | Проверяет наличие имени пользователя в запросе. |
| public | [hasLastName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_hasLastName) |  | Проверяет наличие фамилии пользователя в запросе. |
| public | [hasMetadata()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_hasMetadata) |  | Проверяет, были ли установлены метаданные. |
| public | [hasMiddleName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_hasMiddleName) |  | Проверяет наличие отчества пользователя в запросе. |
| public | [hasType()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_hasType) |  | Проверяет наличие типа персональных данных в запросе. |
| public | [jsonSerialize()](../classes/YooKassa-Common-AbstractObject.md#method_jsonSerialize) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации. |
| public | [offsetExists()](../classes/YooKassa-Common-AbstractObject.md#method_offsetExists) |  | Проверяет наличие свойства. |
| public | [offsetGet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetGet) |  | Возвращает значение свойства. |
| public | [offsetSet()](../classes/YooKassa-Common-AbstractObject.md#method_offsetSet) |  | Устанавливает значение свойства. |
| public | [offsetUnset()](../classes/YooKassa-Common-AbstractObject.md#method_offsetUnset) |  | Удаляет свойство. |
| public | [setFirstName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_setFirstName) |  | Устанавливает имя пользователя. |
| public | [setLastName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_setLastName) |  | Устанавливает фамилию пользователя. |
| public | [setMetadata()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_setMetadata) |  | Устанавливает любые дополнительные данные. |
| public | [setMiddleName()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_setMiddleName) |  | Устанавливает отчество пользователя. |
| public | [setType()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_setType) |  | Устанавливает тип персональных данных. |
| public | [toArray()](../classes/YooKassa-Common-AbstractObject.md#method_toArray) |  | Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации Является алиасом метода AbstractObject::jsonSerialize(). |
| public | [validate()](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md#method_validate) |  | Проверяет на валидность текущий объект |
| protected | [getUnknownProperties()](../classes/YooKassa-Common-AbstractObject.md#method_getUnknownProperties) |  | Возвращает массив свойств которые не существуют, но были заданы у объекта. |
| protected | [setValidationError()](../classes/YooKassa-Common-AbstractRequest.md#method_setValidationError) |  | Устанавливает ошибку валидации. |
| protected | [validatePropertyValue()](../classes/YooKassa-Common-AbstractObject.md#method_validatePropertyValue) |  |  |

---
### Details
* File: [lib/Request/PersonalData/CreatePersonalDataRequest.php](../../lib/Request/PersonalData/CreatePersonalDataRequest.php)
* Package: YooKassa\Model
* Class Hierarchy:  
  * [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)
  * [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)
  * \YooKassa\Request\PersonalData\CreatePersonalDataRequest
* Implements:
  * [\YooKassa\Request\PersonalData\CreatePersonalDataRequestInterface](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequestInterface.md)

* See Also:
  * [](https://yookassa.ru/developers/api)

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| category |  | Class |
| author |  | cms@yoomoney.ru |

---
## Constants
<a name="constant_MAX_LENGTH_LAST_NAME" class="anchor"></a>
###### MAX_LENGTH_LAST_NAME
Максимальная длина строки фамилии или отчества

```php
MAX_LENGTH_LAST_NAME = 200
```


<a name="constant_MAX_LENGTH_FIRST_NAME" class="anchor"></a>
###### MAX_LENGTH_FIRST_NAME
Максимальная длина строки имени

```php
MAX_LENGTH_FIRST_NAME = 100
```



---
## Properties
<a name="property_first_name"></a>
#### public $first_name : string
---
***Description***

Имя пользователя

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_firstName"></a>
#### public $firstName : string
---
***Description***

Имя пользователя

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_last_name"></a>
#### public $last_name : string
---
***Description***

Фамилия пользователя

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_lastName"></a>
#### public $lastName : string
---
***Description***

Фамилия пользователя

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_metadata"></a>
#### public $metadata : \YooKassa\Model\Metadata
---
***Description***

Метаданные персональных данных указанные мерчантом

**Type:** <a href="../classes/YooKassa-Model-Metadata.html"><abbr title="\YooKassa\Model\Metadata">Metadata</abbr></a>

**Details:**


<a name="property_middle_name"></a>
#### public $middle_name : string
---
***Description***

Отчество пользователя

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_middleName"></a>
#### public $middleName : string
---
***Description***

Отчество пользователя

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property_type"></a>
#### public $type : string
---
***Description***

Тип персональных данных

**Type:** <a href="../string"><abbr title="string">string</abbr></a>

**Details:**


<a name="property__metadata"></a>
#### protected $_metadata : ?\YooKassa\Model\Metadata
---
**Summary**

Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа).

***Description***

Передаются в виде набора пар «ключ-значение» и возвращаются в ответе от ЮKassa.
Ограничения: максимум 16 ключей, имя ключа не больше 32 символов, значение ключа не больше 512 символов, тип данных — строка в формате UTF-8.

**Type:** <a href="../?\YooKassa\Model\Metadata"><abbr title="?\YooKassa\Model\Metadata">Metadata</abbr></a>

**Details:**



---
## Methods
<a name="method___construct" class="anchor"></a>
#### public __construct() : mixed

```php
public __construct(array|null $data = []) : mixed
```

**Summary**

AbstractObject constructor.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR null</code> | data  |  |

**Returns:** mixed - 


<a name="method___get" class="anchor"></a>
#### public __get() : mixed

```php
public __get(string $propertyName) : mixed
```

**Summary**

Возвращает значение свойства.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя свойства |

**Returns:** mixed - Значение свойства


<a name="method___isset" class="anchor"></a>
#### public __isset() : bool

```php
public __isset(string $propertyName) : bool
```

**Summary**

Проверяет наличие свойства.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя проверяемого свойства |

**Returns:** bool - True если свойство имеется, false если нет


<a name="method___set" class="anchor"></a>
#### public __set() : void

```php
public __set(string $propertyName, mixed $value) : void
```

**Summary**

Устанавливает значение свойства.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя свойства |
| <code lang="php">mixed</code> | value  | Значение свойства |

**Returns:** void - 


<a name="method___unset" class="anchor"></a>
#### public __unset() : void

```php
public __unset(string $propertyName) : void
```

**Summary**

Удаляет свойство.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  | Имя удаляемого свойства |

**Returns:** void - 


<a name="method_builder" class="anchor"></a>
#### public builder() : \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder

```php
Static public builder() : \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder
```

**Summary**

Возвращает билдер объектов запросов создания платежа.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** \YooKassa\Request\PersonalData\CreatePersonalDataRequestBuilder - Инстанс билдера объектов запросов


<a name="method_clearValidationError" class="anchor"></a>
#### public clearValidationError() : void

```php
public clearValidationError() : void
```

**Summary**

Очищает статус валидации текущего запроса.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)

**Returns:** void - 


<a name="method_fromArray" class="anchor"></a>
#### public fromArray() : void

```php
public fromArray(array|\Traversable $sourceArray) : void
```

**Summary**

Устанавливает значения свойств текущего объекта из массива.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">array OR \Traversable</code> | sourceArray  | Ассоциативный массив с настройками |

**Returns:** void - 


<a name="method_getFirstName" class="anchor"></a>
#### public getFirstName() : string|null

```php
public getFirstName() : string|null
```

**Summary**

Возвращает имя пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** string|null - Имя пользователя


<a name="method_getLastName" class="anchor"></a>
#### public getLastName() : string|null

```php
public getLastName() : string|null
```

**Summary**

Возвращает фамилию пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** string|null - Фамилия пользователя


<a name="method_getLastValidationError" class="anchor"></a>
#### public getLastValidationError() : string|null

```php
public getLastValidationError() : string|null
```

**Summary**

Возвращает последнюю ошибку валидации.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)

**Returns:** string|null - Последняя произошедшая ошибка валидации


<a name="method_getMetadata" class="anchor"></a>
#### public getMetadata() : \YooKassa\Model\Metadata|null

```php
public getMetadata() : \YooKassa\Model\Metadata|null
```

**Summary**

Возвращает любые дополнительные данные.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** \YooKassa\Model\Metadata|null - Любые дополнительные данные


<a name="method_getMiddleName" class="anchor"></a>
#### public getMiddleName() : null|string

```php
public getMiddleName() : null|string
```

**Summary**

Возвращает отчество пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** null|string - Отчество пользователя


<a name="method_getType" class="anchor"></a>
#### public getType() : string|null

```php
public getType() : string|null
```

**Summary**

Возвращает тип персональных данных.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** string|null - Тип персональных данных


<a name="method_getValidator" class="anchor"></a>
#### public getValidator() : \YooKassa\Validator\Validator

```php
public getValidator() : \YooKassa\Validator\Validator
```

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

**Returns:** \YooKassa\Validator\Validator - 


<a name="method_hasFirstName" class="anchor"></a>
#### public hasFirstName() : bool

```php
public hasFirstName() : bool
```

**Summary**

Проверяет наличие имени пользователя в запросе.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** bool - True если имя пользователя задано, false если нет


<a name="method_hasLastName" class="anchor"></a>
#### public hasLastName() : bool

```php
public hasLastName() : bool
```

**Summary**

Проверяет наличие фамилии пользователя в запросе.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** bool - True если фамилия пользователя задана, false если нет


<a name="method_hasMetadata" class="anchor"></a>
#### public hasMetadata() : bool

```php
public hasMetadata() : bool
```

**Summary**

Проверяет, были ли установлены метаданные.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** bool - True если метаданные были установлены, false если нет


<a name="method_hasMiddleName" class="anchor"></a>
#### public hasMiddleName() : bool

```php
public hasMiddleName() : bool
```

**Summary**

Проверяет наличие отчества пользователя в запросе.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** bool - True если отчество пользователя задано, false если нет


<a name="method_hasType" class="anchor"></a>
#### public hasType() : bool

```php
public hasType() : bool
```

**Summary**

Проверяет наличие типа персональных данных в запросе.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** bool - True если тип персональных данных задан, false если нет


<a name="method_jsonSerialize" class="anchor"></a>
#### public jsonSerialize() : array

```php
public jsonSerialize() : array
```

**Summary**

Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

**Returns:** array - Ассоциативный массив со свойствами текущего объекта


<a name="method_offsetExists" class="anchor"></a>
#### public offsetExists() : bool

```php
public offsetExists(string $offset) : bool
```

**Summary**

Проверяет наличие свойства.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя проверяемого свойства |

**Returns:** bool - True если свойство имеется, false если нет


<a name="method_offsetGet" class="anchor"></a>
#### public offsetGet() : mixed

```php
public offsetGet(string $offset) : mixed
```

**Summary**

Возвращает значение свойства.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя свойства |

**Returns:** mixed - Значение свойства


<a name="method_offsetSet" class="anchor"></a>
#### public offsetSet() : void

```php
public offsetSet(string $offset, mixed $value) : void
```

**Summary**

Устанавливает значение свойства.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя свойства |
| <code lang="php">mixed</code> | value  | Значение свойства |

**Returns:** void - 


<a name="method_offsetUnset" class="anchor"></a>
#### public offsetUnset() : void

```php
public offsetUnset(string $offset) : void
```

**Summary**

Удаляет свойство.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | offset  | Имя удаляемого свойства |

**Returns:** void - 


<a name="method_setFirstName" class="anchor"></a>
#### public setFirstName() : self

```php
public setFirstName(string|null $first_name = null) : self
```

**Summary**

Устанавливает имя пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | first_name  | Имя пользователя |

**Returns:** self - 


<a name="method_setLastName" class="anchor"></a>
#### public setLastName() : self

```php
public setLastName(string|null $last_name = null) : self
```

**Summary**

Устанавливает фамилию пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string OR null</code> | last_name  | Фамилия пользователя |

**Returns:** self - 


<a name="method_setMetadata" class="anchor"></a>
#### public setMetadata() : self

```php
public setMetadata(\YooKassa\Model\Metadata|array|null $metadata = null) : self
```

**Summary**

Устанавливает любые дополнительные данные.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\Metadata OR array OR null</code> | metadata  | Любые дополнительные данные |

**Returns:** self - 


<a name="method_setMiddleName" class="anchor"></a>
#### public setMiddleName() : self

```php
public setMiddleName(null|string $middle_name = null) : self
```

**Summary**

Устанавливает отчество пользователя.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">null OR string</code> | middle_name  | Отчество пользователя |

**Returns:** self - 


<a name="method_setType" class="anchor"></a>
#### public setType() : self

```php
public setType(\YooKassa\Model\PersonalData\PersonalDataType|string|null $type = null) : self
```

**Summary**

Устанавливает тип персональных данных.

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">\YooKassa\Model\PersonalData\PersonalDataType OR string OR null</code> | type  | Тип персональных данных |

**Returns:** self - 


<a name="method_toArray" class="anchor"></a>
#### public toArray() : array

```php
public toArray() : array
```

**Summary**

Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации
Является алиасом метода AbstractObject::jsonSerialize().

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

**Returns:** array - Ассоциативный массив со свойствами текущего объекта


<a name="method_validate" class="anchor"></a>
#### public validate() : bool

```php
public validate() : bool
```

**Summary**

Проверяет на валидность текущий объект

**Details:**
* Inherited From: [\YooKassa\Request\PersonalData\CreatePersonalDataRequest](../classes/YooKassa-Request-PersonalData-CreatePersonalDataRequest.md)

**Returns:** bool - True если объект запроса валиден, false если нет


<a name="method_getUnknownProperties" class="anchor"></a>
#### protected getUnknownProperties() : array

```php
protected getUnknownProperties() : array
```

**Summary**

Возвращает массив свойств которые не существуют, но были заданы у объекта.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

**Returns:** array - Ассоциативный массив с не существующими у текущего объекта свойствами


<a name="method_setValidationError" class="anchor"></a>
#### protected setValidationError() : void

```php
protected setValidationError(string $value) : void
```

**Summary**

Устанавливает ошибку валидации.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractRequest](../classes/YooKassa-Common-AbstractRequest.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | value  | Ошибка, произошедшая при валидации объекта |

**Returns:** void - 


<a name="method_validatePropertyValue" class="anchor"></a>
#### protected validatePropertyValue() : mixed

```php
protected validatePropertyValue(string $propertyName, mixed $propertyValue) : mixed
```

**Details:**
* Inherited From: [\YooKassa\Common\AbstractObject](../classes/YooKassa-Common-AbstractObject.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">string</code> | propertyName  |  |
| <code lang="php">mixed</code> | propertyValue  |  |

**Returns:** mixed - 



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