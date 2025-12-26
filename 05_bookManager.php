<?php

    // 05_bookManager.php

    class BookManager { // 責務 = 図書の管理

        private array $books = []; // 登録済みの全図書リスト

        public function register(Book $book) { // 図書登録機能

            $this->books[] = $book; 
            // 引数として受け取った Book オブジェクトを図書リストに保存
        }

        public function findBook(string $bookNum) { // 単一図書検索機能

            foreach($this->books as $book) { // 図書リストを走査

                if($book->getNum() === $bookNum) return $book;
                // 引数として受け取った番号と一致する図書が見つかった場合、その図書を返却
            }
            return null; // 見つからなかった場合は null を返却
        }

        public function isNumRegistered(string $bookNum) {
        // 상태 유효성, 도서 번호 중복 검사 로직

            foreach($this->books as $book) {

                if($book->getNum() === $bookNum) {

                    return true;
                }
            }
            return false;
        }

        public function view() { // 登録済み図書一覧表示機能

            print_r($this->books);
        }
    }

?>