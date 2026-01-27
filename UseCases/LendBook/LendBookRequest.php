<?php

    // LendBookRequest.php

    class LendBookRequest {

        public function __construct(
            
            public string $bookNumber,
            public string $memberName,
            public string $loanDate,
        ) { }
    }
?>