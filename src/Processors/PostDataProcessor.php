<?php

namespace BwtTeam\LaravelErrorMailer\Processors;

class PostDataProcessor
{
    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record) {
        $record['extra']['post'] = $this->getPost();

        return $record;
    }

    /**
     * @return array|false
     */
    protected function getPost() {
        return $_POST;
    }
}