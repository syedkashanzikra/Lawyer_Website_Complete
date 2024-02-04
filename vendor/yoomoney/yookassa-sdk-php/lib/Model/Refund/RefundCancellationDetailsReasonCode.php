<?php

/**
 * The MIT License.
 *
 * Copyright (c) 2023 "YooMoney", NBСO LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace YooKassa\Model\Refund;

use YooKassa\Common\AbstractEnum;

/**
 * Класс, представляющий модель RefundCancellationDetailsReasonCode.
 *
 * Возможные причины отмены возврата:
 * - `general_decline` - Причина не детализирована
 * - `insufficient_funds` - Не хватает денег, чтобы сделать возврат
 * - `rejected_by_payee` - Эмитент платежного средства отклонил возврат по неизвестным причинам
 * - `yoo_money_account_closed` - Пользователь закрыл кошелек ЮMoney, на который вы пытаетесь вернуть платеж
 */
class RefundCancellationDetailsReasonCode extends AbstractEnum
{
    /** Причина не детализирована. Для уточнения подробностей обратитесь в техническую поддержку. */
    public const GENERAL_DECLINE = 'general_decline';

    /** Не хватает денег, чтобы сделать возврат: сумма платежей, которые вы получили в день возврата, меньше, чем сам возврат, или есть задолженность. [Что делать в этом случае](https://yookassa.ru/docs/support/payments/refunding#refunding__block) */
    public const INSUFFICIENT_FUNDS = 'insufficient_funds';

    /** Эмитент платежного средства отклонил возврат по неизвестным причинам. Предложите пользователю обратиться к эмитенту для уточнения подробностей или договоритесь с пользователем о том, чтобы вернуть ему деньги напрямую, не через ЮKassa. */
    public const REJECTED_BY_PAYEE = 'rejected_by_payee';

    /** Пользователь закрыл кошелек ЮMoney, на который вы пытаетесь вернуть платеж. Сделать возврат через ЮKassa нельзя. Договоритесь с пользователем напрямую, каким способом вы вернете ему деньги. */
    public const YOO_MONEY_ACCOUNT_CLOSED = 'yoo_money_account_closed';

    protected static array $validValues = [
        self::GENERAL_DECLINE => true,
        self::INSUFFICIENT_FUNDS => true,
        self::REJECTED_BY_PAYEE => true,
        self::YOO_MONEY_ACCOUNT_CLOSED => true,
    ];
}
