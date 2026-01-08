<?php

    // 06_inputValidator.php

    class InputValidator {

        public function validateLoanInput($bookNumber, $memberName) {

            if (preg_match('/^\d{1,3}$/', $bookNumber) !== 1) return false;
            if (preg_match('/^[A-Za-zぁ-んァ-ヶ一-龠\s]{1,15}$/u', $memberName) !== 1) return false;

            return true;
        }

        public function validateBookInput($bookNumber, $bookName) {

            $bookNumber = trim($bookNumber);

            if (preg_match('/^\d{1,3}$/', $bookNumber) !== 1) return false;
            if (preg_match('/^[A-Za-zぁ-んァ-ヶ一-龠\s]{1,20}$/u', $bookName) !== 1) return false;

            return true;
        }
    }
    // 설계 목적 = 입력 형식 유효성 검사, 의존 분리와 인터페이스의 필요성 체험.
    // 문제점 = application logic과 UI logic이 강하게 결합됨.
    // 추후 설계 변경 시(메서드 이름, 파라미터) application logic과 UI logic까지 수정해야 함.
?>