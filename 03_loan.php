<?php

    // 03_loan.php
    require_once('./01_book.php');
    require_once('./02_member.php');

    class Loan { // 責務 = 単一の図書貸出記録そのもの

        private Book $book; // Book オブジェクト
        private Member $member; // Member オブジェクト
        private string $date; // 貸出発生日

        public function __construct(Book $book, Member $member, string $date) {

            $this->book = $book;
            $this->member = $member;
            $this->date = $date;
        }
        
        public function getMember() { // ローカルストレージ保存機能の実装のために追加

            return $this->member;
        }

        public function getBook() { // Book オブジェクトの getter

            return $this->book;
        }

        public function getDate() { // 貸出発生日 getter

            return $this->date;
        }
    }
    
?>