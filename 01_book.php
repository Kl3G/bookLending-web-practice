<?php

    // 01_Book.php

    class Book { // 責務 = 図書の情報

        private string $number; // 図書番号
        private string $name; // 図書名

        public function __construct(string $number, string $name) {

            $this->number = $number;
            $this->name = $name;
        }

        public function getNum() { // 図書番号の getter

            return $this->number;
        }

        public function getName() { // 図書名の getter

            return $this->name;
        }
    }

?>