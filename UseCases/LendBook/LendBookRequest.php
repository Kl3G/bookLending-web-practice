<?php

    // LendBookRequest.php

    class LendBookRequest {

        public function __construct(
            
            public string $bookNumber, // useCase input Data
            public string $memberName, // useCase input Data
            public string $loanDate // useCase input Data
        ) { }
    }
?>