<?php

    class RegisterBookRequest {

        public function __construct(

            public string $bookNumber, // useCase input Data
            public string $bookName, // useCase input Data
        ) { }
    }

?>