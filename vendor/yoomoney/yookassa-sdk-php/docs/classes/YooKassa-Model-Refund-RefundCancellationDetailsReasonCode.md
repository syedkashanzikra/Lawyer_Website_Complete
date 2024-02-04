# [YooKassa API SDK](../home.md)

# Class: \YooKassa\Model\Refund\RefundCancellationDetailsReasonCode
### Namespace: [\YooKassa\Model\Refund](../namespaces/yookassa-model-refund.md)
---
**Summary:**

Класс, представляющий модель RefundCancellationDetailsReasonCode.

**Description:**

Возможные причины отмены возврата:
- `general_decline` - Причина не детализирована
- `insufficient_funds` - Не хватает денег, чтобы сделать возврат
- `rejected_by_payee` - Эмитент платежного средства отклонил возврат по неизвестным причинам
- `yoo_money_account_closed` - Пользователь закрыл кошелек ЮMoney, на который вы пытаетесь вернуть платеж

---
### Constants
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [GENERAL_DECLINE](../classes/YooKassa-Model-Refund-RefundCancellationDetailsReasonCode.md#constant_GENERAL_DECLINE) |  | Причина не детализирована. Для уточнения подробностей обратитесь в техническую поддержку. |
| public | [INSUFFICIENT_FUNDS](../classes/YooKassa-Model-Refund-RefundCancellationDetailsReasonCode.md#constant_INSUFFICIENT_FUNDS) |  | Не хватает денег, чтобы сделать возврат: сумма платежей, которые вы получили в день возврата, меньше, чем сам возврат, или есть задолженность. [Что делать в этом случае](https://yookassa.ru/docs/support/payments/refunding#refunding__block) |
| public | [REJECTED_BY_PAYEE](../classes/YooKassa-Model-Refund-RefundCancellationDetailsReasonCode.md#constant_REJECTED_BY_PAYEE) |  | Эмитент платежного средства отклонил возврат по неизвестным причинам. Предложите пользователю обратиться к эмитенту для уточнения подробностей или договоритесь с пользователем о том, чтобы вернуть ему деньги напрямую, не через ЮKassa. |
| public | [YOO_MONEY_ACCOUNT_CLOSED](../classes/YooKassa-Model-Refund-RefundCancellationDetailsReasonCode.md#constant_YOO_MONEY_ACCOUNT_CLOSED) |  | Пользователь закрыл кошелек ЮMoney, на который вы пытаетесь вернуть платеж. Сделать возврат через ЮKassa нельзя. Договоритесь с пользователем напрямую, каким способом вы вернете ему деньги. |

---
### Properties
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| protected | [$validValues](../classes/YooKassa-Model-Refund-RefundCancellationDetailsReasonCode.md#property_validValues) |  |  |

---
### Methods
| Visibility | Name | Flag | Summary |
| ----------:| ---- | ---- | ------- |
| public | [getEnabledValues()](../classes/YooKassa-Common-AbstractEnum.md#method_getEnabledValues) |  | Возвращает значения в enum'е значения которых разрешены. |
| public | [getValidValues()](../classes/YooKassa-Common-AbstractEnum.md#method_getValidValues) |  | Возвращает все значения в enum'e. |
| public | [valueExists()](../classes/YooKassa-Common-AbstractEnum.md#method_valueExists) |  | Проверяет наличие значения в enum'e. |

---
### Details
* File: [lib/Model/Refund/RefundCancellationDetailsReasonCode.php](../../lib/Model/Refund/RefundCancellationDetailsReasonCode.php)
* Package: Default
* Class Hierarchy: 
  * [\YooKassa\Common\AbstractEnum](../classes/YooKassa-Common-AbstractEnum.md)
  * \YooKassa\Model\Refund\RefundCancellationDetailsReasonCode

---
## Constants
<a name="constant_GENERAL_DECLINE" class="anchor"></a>
###### GENERAL_DECLINE
Причина не детализирована. Для уточнения подробностей обратитесь в техническую поддержку.

```php
GENERAL_DECLINE = 'general_decline'
```


<a name="constant_INSUFFICIENT_FUNDS" class="anchor"></a>
###### INSUFFICIENT_FUNDS
Не хватает денег, чтобы сделать возврат: сумма платежей, которые вы получили в день возврата, меньше, чем сам возврат, или есть задолженность. [Что делать в этом случае](https://yookassa.ru/docs/support/payments/refunding#refunding__block)

```php
INSUFFICIENT_FUNDS = 'insufficient_funds'
```


<a name="constant_REJECTED_BY_PAYEE" class="anchor"></a>
###### REJECTED_BY_PAYEE
Эмитент платежного средства отклонил возврат по неизвестным причинам. Предложите пользователю обратиться к эмитенту для уточнения подробностей или договоритесь с пользователем о том, чтобы вернуть ему деньги напрямую, не через ЮKassa.

```php
REJECTED_BY_PAYEE = 'rejected_by_payee'
```


<a name="constant_YOO_MONEY_ACCOUNT_CLOSED" class="anchor"></a>
###### YOO_MONEY_ACCOUNT_CLOSED
Пользователь закрыл кошелек ЮMoney, на который вы пытаетесь вернуть платеж. Сделать возврат через ЮKassa нельзя. Договоритесь с пользователем напрямую, каким способом вы вернете ему деньги.

```php
YOO_MONEY_ACCOUNT_CLOSED = 'yoo_money_account_closed'
```



---
## Properties
<a name="property_validValues"></a>
#### protected $validValues : array
---
**Type:** <a href="../array"><abbr title="array">array</abbr></a>
Массив принимаемых enum&#039;ом значений
**Details:**



---
## Methods
<a name="method_getEnabledValues" class="anchor"></a>
#### public getEnabledValues() : string[]

```php
Static public getEnabledValues() : string[]
```

**Summary**

Возвращает значения в enum'е значения которых разрешены.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractEnum](../classes/YooKassa-Common-AbstractEnum.md)

**Returns:** string[] - Массив разрешённых значений


<a name="method_getValidValues" class="anchor"></a>
#### public getValidValues() : array

```php
Static public getValidValues() : array
```

**Summary**

Возвращает все значения в enum'e.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractEnum](../classes/YooKassa-Common-AbstractEnum.md)

**Returns:** array - Массив значений в перечислении


<a name="method_valueExists" class="anchor"></a>
#### public valueExists() : bool

```php
Static public valueExists(mixed $value) : bool
```

**Summary**

Проверяет наличие значения в enum'e.

**Details:**
* Inherited From: [\YooKassa\Common\AbstractEnum](../classes/YooKassa-Common-AbstractEnum.md)

##### Parameters:
| Type | Name | Description |
| ---- | ---- | ----------- |
| <code lang="php">mixed</code> | value  | Проверяемое значение |

**Returns:** bool - True если значение имеется, false если нет



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