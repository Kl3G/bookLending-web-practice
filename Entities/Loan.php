<?php

    // Loan.php

    class Loan { // entity

        public function __construct(
            
            private string $bookNumber, // domain data
            private String $memberName, // domain data
            private string $date // domain data
        ) { }

        public function bookNumber(): string { // query

            return $this->bookNumber;
        }

        public function memberName(): string { // query

            return $this->memberName;
        }

        public function date(): string { // query

            return $this->date;
        }

        public function hasBookNumber($number): bool { // query

            return $number === $this->bookNumber;
        }
    }
    
?>