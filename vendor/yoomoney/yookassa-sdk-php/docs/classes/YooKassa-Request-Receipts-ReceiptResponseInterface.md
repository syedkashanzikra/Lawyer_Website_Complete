# [YooKassa API SDK](../home.md)

# Interface: ReceiptResponseInterface
### Namespace: [\YooKassa\Request\Receipts](../namespaces/yookassa-request-receipts.md)
---
**Summary:**

Interface ReceiptInterface.

---
### Constants
* No constants found

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [getId()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_getId) |  | Возвращает идентификатор чека в ЮKassa. |
| public | [getItems()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_getItems) |  | Возвращает список товаров в заказ. |
| public | [getOnBehalfOf()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_getOnBehalfOf) |  | Возвращает идентификатор магазин |
| public | [getSettlements()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_getSettlements) |  | Возвращает список расчетов. |
| public | [getStatus()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_getStatus) |  | Возвращает статус доставки данных для чека в онлайн-кассу. |
| public | [getTaxSystemCode()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_getTaxSystemCode) |  | Возвращает код системы налогообложения. |
| public | [getType()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_getType) |  | Возвращает тип чека в онлайн-кассе. |
| public | [notEmpty()](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md#method_notEmpty) |  | Проверяет есть ли в чеке хотя бы одна позиция. |

---
### Details
* File: [lib/Request/Receipts/ReceiptResponseInterface.php](../../lib/Request/Receipts/ReceiptResponseInterface.php)
* Package: \YooKassa\Request
* See Also:
  * [](https://yookassa.ru/developers/api)

---
### Tags
| Tag | Version | Description |
| --- | ------- | ----------- |
| category |  | Interface |
| author |  | cms@yoomoney.ru |
| property |  | Идентификатор чека в ЮKassa. |
| property |  | Тип чека в онлайн-кассе: приход "payment" или возврат "refund". |
| property |  | Статус доставки данных для чека в онлайн-кассу ("pending", "succeeded" или "canceled"). |
| property |  | Статус доставки данных для чека в онлайн-кассу ("pending", "succeeded" или "canceled"). |
| property |  | Статус доставки данных для чека в онлайн-кассу ("pending", "succeeded" или "canceled"). |
| property |  | Код системы налогообложения. Число 1-6. |
| property |  | Код системы налогообложения. Число 1-6. |
| property |  | Список товаров в заказе |
| property |  | Список товаров в заказе |

---
## Methods
<a name="method_getId" class="anchor"></a>
#### public getId() : string|null

```php
public getId() : string|null
```

**Summary**

Возвращает идентификатор чека в ЮKassa.

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** string|null - Идентификатор чека в ЮKassa


<a name="method_getType" class="anchor"></a>
#### public getType() : string|null

```php
public getType() : string|null
```

**Summary**

Возвращает тип чека в онлайн-кассе.

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** string|null - Тип чека в онлайн-кассе: приход "payment" или возврат "refund"


<a name="method_getStatus" class="anchor"></a>
#### public getStatus() : string|null

```php
public getStatus() : string|null
```

**Summary**

Возвращает статус доставки данных для чека в онлайн-кассу.

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** string|null - Статус доставки данных для чека в онлайн-кассу ("pending", "succeeded" или "canceled")


<a name="method_getTaxSystemCode" class="anchor"></a>
#### public getTaxSystemCode() : int|null

```php
public getTaxSystemCode() : int|null
```

**Summary**

Возвращает код системы налогообложения.

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** int|null - Код системы налогообложения. Число 1-6.


<a name="method_getItems" class="anchor"></a>
#### public getItems() : \YooKassa\Common\ListObjectInterface

```php
public getItems() : \YooKassa\Common\ListObjectInterface
```

**Summary**

Возвращает список товаров в заказ.

**Description**

 @return ReceiptResponseItemInterface[]|ListObjectInterface

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** \YooKassa\Common\ListObjectInterface - 


<a name="method_getSettlements" class="anchor"></a>
#### public getSettlements() : \YooKassa\Common\ListObjectInterface

```php
public getSettlements() : \YooKassa\Common\ListObjectInterface
```

**Summary**

Возвращает список расчетов.

**Description**

 @return SettlementInterface[]|ListObjectInterface

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** \YooKassa\Common\ListObjectInterface - 


<a name="method_getOnBehalfOf" class="anchor"></a>
#### public getOnBehalfOf() : string|null

```php
public getOnBehalfOf() : string|null
```

**Summary**

Возвращает идентификатор магазин

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** string|null - 


<a name="method_notEmpty" class="anchor"></a>
#### public notEmpty() : bool

```php
public notEmpty() : bool
```

**Summary**

Проверяет есть ли в чеке хотя бы одна позиция.

**Details:**
* Inherited From: [\YooKassa\Request\Receipts\ReceiptResponseInterface](../classes/YooKassa-Request-Receipts-ReceiptResponseInterface.md)

**Returns:** bool - True если чек не пуст, false если в чеке нет ни одной позиции




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