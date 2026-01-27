<?php

    // Book.php

    class Book { // entity

        private bool $isLent = false; // domain state

        public function __construct(

            private string $number, // domain data
            private string $name, // domain data
        ) { }

        // A predicate does not change state or data; it only returns a result
        public function number(): string { // query

            return $this->number;
        }

        public function name(): string { // query

            return $this->name;
        }

        public function isLent(): bool { // predicate

            return $this->isLent;
        }

        public function hasNumber(string $number): bool { // predicate

            return $number === $this->number;
        }

        public function lend(): void { // enterprise Business Rules

            if($this->isLent) throw new Exception("This book is already on loan.");
            // Domain-related errors should be handled as DomainExceptions

            $this->isLent = true;
        }

        public function endLoan(): void { // enterprise Business Rules

            if(!($this->isLent)) throw new Exception("This book is not on loan.");
            // Domain-related errors should be handled as DomainExceptions
                
            $this->isLent = false;
        }
    }

?>