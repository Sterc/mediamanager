<?php
namespace Sterc\MediaManager\Traits;

trait ErrorTrait
{
    /**
     * @var array $errors Holds all error messages.
     */
    protected $errors = [];

    /**
     * Add an error message.
     * @param string $key
     * @param string $msg
     */
    public function addError($key, $msg = '')
    {
        $this->errors[$key] = $msg;
    }

    /**
     * Return all errors.
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retrieve error by key.
     * @param $key
     * @return bool|string
     */
    public function getError($key)
    {
        if (isset($this->errors[$key])) {
            return $this->errors[$key];
        }

        return false;
    }

    /**
     * Determine if there are any errors.
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
}
