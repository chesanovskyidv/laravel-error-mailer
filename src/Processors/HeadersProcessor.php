<?php

namespace BwtTeam\LaravelErrorMailer\Processors;

class HeadersProcessor
{
    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record) {
        $record['extra']['headers'] = $this->getHeaders();

        return $record;
    }

    /**
     * @return array|false
     */
    protected function getHeaders() {
        return apache_request_headers();
    }
}