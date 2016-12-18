<?php

    class UltimateCMS_Collections_JsonResponse implements JsonSerializable {

        protected $_status = 'ok';
        protected $_statusMessage = '';
        protected $payload = array();

        public function getStatus() {
            return $this->_status;
        }

        public function getStatusMessage() {
            return $this->_statusMessage;
        }

        public function getPayload() {
            return $this->payload;
        }

        protected function setStatus($_status) {
            $this->_status = $_status;
            return $this;
        }

        public function setStatusMessage($_statusMessage) {
            $this->_statusMessage = $_statusMessage;
            return $this;
        }

        public function setPayload($payload) {
            $this->payload = $payload;
            return $this;
        }

        public function statusIsError() {
            $this->setStatus('error');
            return $this;
        }

        public function statusIsOk() {
            $this->setStatus('ok');
            return $this;
        }

        public function setPayloadKey($key, $value) {
            $this->payload[$key] = $value;
            return $this;
        }

        public function jsonSerialize() {
            return array(
                'status' => $this->getStatus(),
                'statusMessage' => $this->getStatusMessage(),
                'payload' => $this->getPayload(),
            );
        }

    }
