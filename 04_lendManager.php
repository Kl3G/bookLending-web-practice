<?php

    // 04_lendManager.php
    require_once('./03_loan.php');

    class LendManager { // 責務 = 図書貸出の管理


        private array $loans = []; // 全体の図書貸出記録

        public function getLoans() { // ローカルストレージ保存機能の実装のために追加

            return $this->loans;
        }

        public function lendTo(Book $book, Member $member, string $date) { // 図書貸出機能

            $loan = new Loan($book, $member, $date);
            // 引数として受け取った Book, Member オブジェクトで
            // 単一の図書貸出記録を生成
            $this->loans[] = $loan; // 単一の貸出記録を配列に保存
        }

        public function returnFrom(Book $book) { // 図書返却機能

            foreach($this->loans as $index => $loan) {
            // 全体の貸出記録を走査

                if($book === $loan->getBook()) {
                // 返却対象の Book オブジェクトを保持している単一の貸出記録を検索

                    unset($this->loans[$index]); // 一致した貸出記録を配列から削除
                    $this->loans = array_values($this->loans); // 削除後の配列インデックスを整理
                    return true; // foreach 外の echo が常に実行されるのを防ぐ 
                }
            }
            return false;
        }

        public function findLoanByBookNum(string $bookNum) {
        // 特定の図書に対する単一の貸出記録を検索する機能

            foreach($this->loans as $loan) { // 全体の貸出記録を走査

                $foundBook = $loan->getBook(); // 単一の貸出記録に紐づく図書オブジェクトを取得

                if($foundBook->getNum() === $bookNum) return $loan;
                // 取得した図書番号が引数の番号と一致した場合、対応する貸出記録を返却
            }
            return null;
        }

        // findLoanByBookNum メソッドは、元々は下記のような形。
        // しかし、Book 型を返却する設計にすると、
        // LendManager クラスが本来の「貸出管理」ではなく
        // 「図書の取得」を担うように見えてしまい、
        // クラス構造と凝集度が低下すると判断したため、設計を修正。
        /* public function findLendedBook($bookNum) { 

            foreach($this->loans as $loan) {

                $foundBook = $loan->getBook();

                if($foundBook->getNum() === $bookNum) return $foundBook;
            }
            return null;
        } */

        public function isBookLent(string $bookNum) {
        // 状態チェック（重複貸出の検証）ロジック

            foreach($this->loans as $loan) {

                $gotBookNum = $loan->getBook()->getNum();

                if($bookNum === $gotBookNum) {

                   return true; 
                }
            }
            return false;
        }

        public function view() { // 全貸出記録の表示機能

            print_r($this->loans);
        }
    }

?>