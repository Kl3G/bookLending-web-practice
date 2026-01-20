<?php

    // 02_Member.php

    class Member { // 責務 = 図書の利用者情報

        private string $name; // 利用者名

        public function __construct(string $name) {

            $this->name = $name;
        }

        public function getName() { // 利用者名の getter

            return $this->name;
        }
    }

?>