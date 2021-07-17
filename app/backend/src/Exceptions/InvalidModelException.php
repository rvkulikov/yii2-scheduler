<?php
namespace Rvkulikov\Yii2\Scheduler\Exceptions;

use Exception;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\HttpException;

class InvalidModelException extends HttpException
{
    public function __construct(
        public Model $model,
        $message = null,
        $code = 0,
        Exception $previous = null
    ) {
        $message ??= $this->getDefaultMessage();
        parent::__construct(400, $message, $code, $previous);
    }

    public function getDefaultMessage(): string
    {
        $error   = Json::errorSummary($this->model);
        $error   = Json::decode($error);
        $error   = Json::encode($error, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $class   = get_class($this->model);
        $message = "Model {$class} is invalid:\n{$error}";

        return trim($message);
    }

    public function getName(): string
    {
        return "Model is invalid";
    }
}