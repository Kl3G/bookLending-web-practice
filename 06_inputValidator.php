<?php

    // 06_inputValidator.php

    interface InputValidator {

        public function validate($input1, $input2);
    }

    class LoanInputValidator implements InputValidator {

        public function validate($bookNumber, $memberName) {

            if (preg_match('/^\d{1,3}$/', $bookNumber) !== 1) return false;
            if (preg_match('/^[A-Za-zぁ-んァ-ヶ一-龠\s]{1,15}$/u', $memberName) !== 1) return false;

            return true;
        }
    }

    class BookInputValidator implements InputValidator {

        public function validate($bookNumber, $bookName) {

            $bookNumber = trim($bookNumber);

            if (preg_match('/^\d{1,3}$/', $bookNumber) !== 1) return false;
            if (preg_match('/^[A-Za-zぁ-んァ-ヶ一-龠\s]{1,20}$/u', $bookName) !== 1) return false;

            return true;
        }
    }
    // 설계 목적 = 입력 형식 유효성 검사, 의존 분리와 인터페이스의 이해와 숙달.

    // 하지 않은 것 = 유효성 검사의 반복 구조를 최적화하기 위해 상속을 사용하는 것.

    // 하지 않은 이유 = 
    // 상속은 중복 구조 해결이나 기술적 편의가 목적이 아니다.
    // 공통 상태, 공통 로직, 다형성 등은 상속의 결과일 뿐이다.
    // 상속의 목적은 현실에 존재하는 개념을 종류-분류 관계로 표현하는 것이다.
    // 즉, 사람의 사고를 코드 구조로 옮겨서 이해하고 설명하기 위함이며
    // 설계할 때도 이 목적을 상속 사용의 대원칙으로 해야 한다.

    // 대책 = 인터페이스 사용

    // 메모 = 
    // 1. 인터페이스는 같은 역할(외부에 보장되는 행위)을 
    // 하나로 묶어서 구현에 의존하지 않게 한다. 
    // 즉, 결합도를 낮추기 위한 설계를 약속이 아닌 구조로 강제시키는 방법.
    // 타입 명시하지 않고 메서드 이름만 맞춰도 의존 분리는 가능하지만,
    // 구조적으로 강제하기 위해 인터페이스를 사용한다.

    // 2. 도메인은 해결하려는 문제와 직접 관련된 현실의 개념 영역.

    // 3. 결합은 특정한 기술적 개념이 아니다,
    // 모든 설계에 반드시 바탕이 되어야 하는 최상위 개념.
    // 인터페이스 → 결합도를 낮추기 위한 설계를 구조적으로 강제하는 수단.
    // 조합 → 둘 이상의 도메인 개념을 하나의 의미 단위로 결합시키는 수단.
    // 상속 → 도메인 개념들을 종류-분류 관계로 결합시키는 수단.
?>