<?php

    class ReturnBookUsecase {

        function __construct(public LendManager $lendManager) { }

        function returnBook(string $bookNum): void {

            $foundLoan = $this->lendManager->findLoanByBookNum($bookNum);
            // 入力された番号を持つ貸出記録を検索

            if($foundLoan === null) {
                throw new Exception("No results."); // 検索失敗時
            }

            $gotBook = $foundLoan->getBook();
            // Entity 내부 상태에 대한 직접 Access
            // Use cases 의 책임은 Application BR 를 Entity 에 적용한 흐름을 구현하는 것
            
            if(!($this->lendManager->returnFrom($gotBook))) {
                throw new Exception("No results."); // 返却可能な図書が見つからなかった場合
            } // 
        }
    }

?>