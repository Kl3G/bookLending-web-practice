<?php

    // Book.php

    class Book { // entity

        private bool $isLent = false; // domain state

        public function __construct(

            private string $number, // domain data
            private string $name // domain data
        ) { }

        // A query does not change domain state and returns information
        public function number(): string { // query

            return $this->number;
        }

        public function name(): string { // query

            return $this->name;
        }

        public function isLent(): bool { // query

            return $this->isLent;
        }

        public function hasNumber(string $number): bool { // query

            return $number === $this->number;
        }

        // A command changes domain state and may return information
        public function lend(): void { // command(enterprise Business Rules)

            if($this->isLent) throw new Exception("This book is already on loan.");
            // Domain-related errors should be handled as DomainExceptions

            $this->isLent = true;
        }

        public function endLoan(): void { // command(enterprise Business Rules)

            if(!($this->isLent)) throw new Exception("This book is not on loan.");
            // Domain-related errors should be handled as DomainExceptions
                
            $this->isLent = false;
        }
    }

?>