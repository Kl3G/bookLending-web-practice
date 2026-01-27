<?php

    // Member.php

    class Member { // entity

        public function __construct(

            private string $name, // domain data
        ) { }

        public function snapshot() {

            return $data = [

                "user" => $this->name,
            ];
        }
    }

?>