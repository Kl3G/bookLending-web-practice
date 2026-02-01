<?php

    // Loan.php

    class Loan { // entity

        public function __construct(
            
            private string $bookNumber, // domain data
            private String $memberName, // domain data
            private string $date, // domain data
        ) { }

        public function hasBookNumber($number): bool {

            return $number === $this->bookNumber;
        }
    }
    
?>