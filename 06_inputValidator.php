<?php

    // 06_inputValidator.php

    // interface 사용 취소 = 
    // 입력 검증이라는 같은 책임을 가지지만, 그 책임이 향하는 대상(입력의 의미)이 다르다.
    // LoanInputValidator 는 $memberName 를 검증, BookInputValidator 는 $bookName 를 검증한다.
    // 이때 method 를 validate(string, string)처럼 interface 로 선언하면
    // argument 가 잘못 전달되거나 Validator 가 잘못 선택되어도 error 없이 실행되기 때문에 위험하다.
    // 즉, 애초에 두 Validator 를 서로 교체하며 사용할 이유가 거의 없고,
    // 책임과 그 대상(입력의 의미)도 동일하지 않아 약속으로 묶기 어렵다.

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