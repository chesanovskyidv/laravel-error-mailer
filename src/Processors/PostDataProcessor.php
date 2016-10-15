<?php

namespace BwtTeam\LaravelErrorMailer\Processors;

class PostDataProcessor
{
    /** @var array|\ArrayAccess */
    protected $postData;

    /**
     * PostDataProcessor constructor.
     * @param null $postData
     */
    public function __construct($postData = null)
    {
        if (null === $postData) {
            $this->postData = &$_POST;
        } elseif (is_array($postData) || $postData instanceof \ArrayAccess) {
            $this->postData = $postData;
        } else {
            throw new \UnexpectedValueException('$postData must be an array or object implementing ArrayAccess.');
        }
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['extra']['post'] = $this->getPost();

        return $record;
    }

    /**
     * @return array|false
     */
    protected function getPost()
    {
        return $this->postData;
    }
}