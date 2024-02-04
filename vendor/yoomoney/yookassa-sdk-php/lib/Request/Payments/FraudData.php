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

namespace YooKassa\Request\Payments;

use Exception;
use YooKassa\Common\AbstractObject;
use YooKassa\Validator\Constraints as Assert;

/**
 * Класс, представляющий модель FraudData.
 *
 * Информация для проверки операции на мошенничество
 *
 * @category Class
 * @package  YooKassa\Request
 * @author   cms@yoomoney.ru
 * @link     https://yookassa.ru/developers/api
 *
 * @property string $toppedUpPhone Номер телефона для пополнения
 * @property string $topped_up_phone Номер телефона для пополнения
 */
class FraudData extends AbstractObject
{
    /**
     * Номер телефона для пополнения. Не более 15 символов. Пример: ~`79110000000`.
     * Необходим при [пополнении баланса телефона](/developers/payment-acceptance/scenario-extensions/top-up-phones-balance).
     * @var string|null
     */
    #[Assert\Type('string')]
    #[Assert\Regex("/^[0-9]{4,15}$/")]
    private ?string $_topped_up_phone = null;

    /**
     * Возвращает номер телефона для пополнения.
     *
     * @return string|null
     */
    public function getToppedUpPhone(): ?string
    {
        return $this->_topped_up_phone;
    }

    /**
     * Устанавливает Номер телефона для пополнения.
     *
     * @param string|null $topped_up_phone Номер телефона для пополнения. Не более 15 символов. Пример: ~`79110000000`
     *
     * @return self
     */
    public function setToppedUpPhone(?string $topped_up_phone = null): self
    {
        $this->_topped_up_phone = $this->validatePropertyValue('_topped_up_phone', $topped_up_phone);
        return $this;
    }
}
