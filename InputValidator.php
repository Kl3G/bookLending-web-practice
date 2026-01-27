<?php

    // InputValidator.php

    class LoanInputValidator {

        public function validate(string $bookNumber, string $memberName): bool {

            if (preg_match('/^\d{1,3}$/', $bookNumber) !== 1) return false;
            if (preg_match('/^[A-Za-zぁ-んァ-ヶ一-龠\s]{1,15}$/u', $memberName) !== 1) return false;

            return true;
        }
    }

    class BookInputValidator {

        public function validate(string $bookNumber, string $bookName): bool {

            $bookNumber = trim($bookNumber);

            if (preg_match('/^\d{1,3}$/', $bookNumber) !== 1) return false;
            if (preg_match('/^[A-Za-zぁ-んァ-ヶ一-龠\s]{1,20}$/u', $bookName) !== 1) return false;

            return true;
        }
    }
?>