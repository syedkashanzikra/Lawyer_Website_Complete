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

namespace YooKassa\Request\PersonalData;

use YooKassa\Common\AbstractRequest;
use YooKassa\Model\Metadata;
use YooKassa\Model\PersonalData\PersonalDataType;
use YooKassa\Validator\Constraints as Assert;

/**
 * Класс, представляющий модель CreatePersonalDataRequest.
 *
 * @category Class
 * @package  YooKassa\Model
 * @author   cms@yoomoney.ru
 * @link     https://yookassa.ru/developers/api
 *
 * @example 02-builder.php 210 20 Пример использования билдера
 *
 * @property string $type Тип персональных данных
 * @property string $lastName Фамилия пользователя
 * @property string $last_name Фамилия пользователя
 * @property string $firstName Имя пользователя
 * @property string $first_name Имя пользователя
 * @property string $middleName Отчество пользователя
 * @property string $middle_name Отчество пользователя
 * @property Metadata $metadata Метаданные персональных данных указанные мерчантом
 */
class CreatePersonalDataRequest extends AbstractRequest implements CreatePersonalDataRequestInterface
{
    /** Максимальная длина строки фамилии или отчества */
    public const MAX_LENGTH_LAST_NAME = 200;

    /** Максимальная длина строки имени */
    public const MAX_LENGTH_FIRST_NAME = 100;

    /**
     * Тип персональных данных — цель, для которой вы будете использовать данные
     *
     * @var string|null
     */
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [PersonalDataType::class, 'getValidValues'])]
    #[Assert\Type('string')]
    private ?string $_type = null;

    /**
     * Фамилия пользователя.
     *
     * @var string|null
     */
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: self::MAX_LENGTH_LAST_NAME)]
    #[Assert\Length(min: 1)]
    #[Assert\Regex("/^[—–‐\\-a-zA-Zа-яёчА-ЯЁЧ ]*$/")]
    private ?string $_last_name = null;

    /**
     * Имя пользователя.
     *
     * @var string|null
     */
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(max: self::MAX_LENGTH_FIRST_NAME)]
    #[Assert\Length(min: 1)]
    #[Assert\Regex("/^[—–‐\\-a-zA-Zа-яёчА-ЯЁЧ ]*$/")]
    private ?string $_first_name = null;

    /**
     * Отчество пользователя. Обязательный параметр, если есть в паспорте.
     *
     * @var string|null
     */
    #[Assert\Type('string')]
    #[Assert\Length(max: self::MAX_LENGTH_LAST_NAME)]
    #[Assert\Length(min: 1)]
    #[Assert\Regex("/^[—–‐\\-a-zA-Zа-яёчА-ЯЁЧ ]*$/")]
    private ?string $_middle_name = null;

    /**
     * Любые дополнительные данные, которые нужны вам для работы (например, ваш внутренний идентификатор заказа).
     *
     * Передаются в виде набора пар «ключ-значение» и возвращаются в ответе от ЮKassa.
     * Ограничения: максимум 16 ключей, имя ключа не больше 32 символов, значение ключа не больше 512 символов, тип данных — строка в формате UTF-8.
     */
    #[Assert\Type(Metadata::class)]
    protected ?Metadata $_metadata = null;

    /**
     * Возвращает тип персональных данных.
     *
     * @return string|null Тип персональных данных
     */
    public function getType(): ?string
    {
        return $this->_type;
    }

    /**
     * Устанавливает тип персональных данных.
     *
     * @param PersonalDataType|string|null $type Тип персональных данных
     *
     * @return self
     */
    public function setType(mixed $type = null): self
    {
        $this->_type = $this->validatePropertyValue('_type', $type);
        return $this;
    }

    /**
     * Проверяет наличие типа персональных данных в запросе.
     *
     * @return bool True если тип персональных данных задан, false если нет
     */
    public function hasType(): bool
    {
        return !empty($this->_type);
    }


    /**
     * Возвращает фамилию пользователя.
     *
     * @return string|null Фамилия пользователя
     */
    public function getLastName(): ?string
    {
        return $this->_last_name;
    }

    /**
     * Устанавливает фамилию пользователя.
     *
     * @param string|null $last_name Фамилия пользователя
     *
     * @return self
     */
    public function setLastName(?string $last_name = null): self
    {
        $this->_last_name = $this->validatePropertyValue('_last_name', $last_name);
        return $this;
    }

    /**
     * Проверяет наличие фамилии пользователя в запросе.
     *
     * @return bool True если фамилия пользователя задана, false если нет
     */
    public function hasLastName(): bool
    {
        return !empty($this->_last_name);
    }

    /**
     * Возвращает имя пользователя.
     *
     * @return string|null Имя пользователя
     */
    public function getFirstName(): ?string
    {
        return $this->_first_name;
    }

    /**
     * Устанавливает имя пользователя.
     *
     * @param string|null $first_name Имя пользователя
     *
     * @return self
     */
    public function setFirstName(?string $first_name = null): self
    {
        $this->_first_name = $this->validatePropertyValue('_first_name', $first_name);
        return $this;
    }

    /**
     * Проверяет наличие имени пользователя в запросе.
     *
     * @return bool True если имя пользователя задано, false если нет
     */
    public function hasFirstName(): bool
    {
        return !empty($this->_first_name);
    }

    /**
     * Возвращает отчество пользователя.
     *
     * @return null|string Отчество пользователя
     */
    public function getMiddleName(): ?string
    {
        return $this->_middle_name;
    }

    /**
     * Устанавливает отчество пользователя.
     *
     * @param null|string $middle_name Отчество пользователя
     *
     * @return self
     */
    public function setMiddleName(?string $middle_name = null): self
    {
        $this->_middle_name = $this->validatePropertyValue('_middle_name', $middle_name);
        return $this;
    }

    /**
     * Проверяет наличие отчества пользователя в запросе.
     *
     * @return bool True если отчество пользователя задано, false если нет
     */
    public function hasMiddleName(): bool
    {
        return !empty($this->_middle_name);
    }

    /**
     * Возвращает любые дополнительные данные.
     *
     * @return Metadata|null Любые дополнительные данные
     */
    public function getMetadata(): ?Metadata
    {
        return $this->_metadata;
    }

    /**
     * Устанавливает любые дополнительные данные.
     *
     * @param Metadata|array|null $metadata Любые дополнительные данные
     *
     * @return self
     */
    public function setMetadata(mixed $metadata = null): self
    {
        $this->_metadata = $this->validatePropertyValue('_metadata', $metadata);
        return $this;
    }

    /**
     * Проверяет, были ли установлены метаданные.
     *
     * @return bool True если метаданные были установлены, false если нет
     */
    public function hasMetadata(): bool
    {
        return !empty($this->_metadata) && $this->_metadata->count() > 0;
    }

    /**
     * Проверяет на валидность текущий объект
     *
     * @return bool True если объект запроса валиден, false если нет
     */
    public function validate(): bool
    {
        if (!$this->hasType()) {
            $this->setValidationError('PersonalData type not specified');

            return false;
        }
        if (!$this->hasLastName()) {
            $this->setValidationError('PersonalData last_name not specified');

            return false;
        }
        if (!$this->hasFirstName()) {
            $this->setValidationError('PersonalData first_name not specified');

            return false;
        }

        return true;
    }

    /**
     * Возвращает билдер объектов запросов создания платежа.
     *
     * @return CreatePersonalDataRequestBuilder Инстанс билдера объектов запросов
     */
    public static function builder(): CreatePersonalDataRequestBuilder
    {
        return new CreatePersonalDataRequestBuilder();
    }
}
