<?php

namespace YooKassa\Model\Webhook;


/**
 * Interface WebhookInterface.
 *
 * @category Interface
 * @package  YooKassa\Model
 * @author   cms@yoomoney.ru
 * @link     https://yookassa.ru/developers/api
 *
 * @property string $id Идентификатор webhook
 * @property string $event Событие, о котором уведомляет ЮKassa
 * @property string $url URL, на который ЮKassa будет отправлять уведомления
 */
interface WebhookInterface
{
    /**
     * Возвращает идентификатор webhook.
     *
     * @return string|null Идентификатор webhook
     */
    public function getId(): ?string;

    /**
     * Устанавливает идентификатор webhook.
     *
     * @param string|null $id Идентификатор webhook
     *
     * @return self
     */
    public function setId(?string $id = null): self;

    /**
     * Возвращает событие, о котором уведомляет ЮKassa.
     *
     * @return string Событие, о котором уведомляет ЮKassa
     */
    public function getEvent(): string;

    /**
     * Устанавливает событие, о котором уведомляет ЮKassa.
     *
     * @param string|null $event Событие, о котором уведомляет ЮKassa
     *
     * @return self
     */
    public function setEvent(?string $event = null): self;

    /**
     * Возвращает URL, на который ЮKassa будет отправлять уведомления.
     *
     * @return string URL, на который ЮKassa будет отправлять уведомления
     */
    public function getUrl(): string;

    /**
     * Устанавливает URL, на который ЮKassa будет отправлять уведомления.
     *
     * @param string|null $url URL, на который ЮKassa будет отправлять уведомления
     *
     * @return self
     */
    public function setUrl(?string $url = null): self;
}