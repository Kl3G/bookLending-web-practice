<?php

    // 05_bookManager.php

    class BookManager { // 責務 = 図書の管理

        private array $books = []; // 登録済みの全図書リスト

        public function getBooks() { // ローカルストレージ保存機能の実装のために追加

            return $this->books;
        }

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
        // 状態チェック（図書番号の重複検証）ロジック

            foreach($this->books as $book) {

                if($book->getNum() === $bookNum) {

                    return true;
                }
            }
            return false;
        }

        public function deleteBook(string $bookNum): bool { // 削除メソッド（削除対象の書籍番号を受け取る）
            
            foreach($this->books as $index => $book) { // 書籍一覧を走査

                if($bookNum === $book->getNum()) { // 入力された番号と書籍番号が一致した場合

                    unset($this->books[$index]); // 削除処理を実行
                    $this->books = array_values($this->books); // 削除後、配列の index を詰め直す

                    return true; // 削除成功を返す
                }
            }
            return false; // 削除対象が存在しないことを返す
        }

        public function view() { // 登録済み図書一覧表示機能

            print_r($this->books);
        }
    }

?>