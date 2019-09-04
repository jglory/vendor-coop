<?php
namespace Apps\Traits;


trait GetEntityErrorMessages
{
    public function getEntityErrorMessages(\Phalcon\Mvc\Model $entity) : string
    {
        $messages = $entity->getMessages();
        $errorMessage = '';
        array_walk($messages, function ($value, $key) use (&$errorMessage) {
            $errorMessage .= (empty($errorMessage) ? '' : ' / ') . $value;
        });

        return $errorMessage;
    }
}