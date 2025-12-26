<?php

    // index.php
    require_once('./01_book.php');
    require_once('./02_member.php');
    require_once('./04_lendManager.php');
    require_once('./05_bookManager.php');

    $lendManager = new LendManager();
    $bookManager = new BookManager();

    while(true) {

        $method = readline( // メニュー表示およびユーザー入力取得

            "\n Select a number: \n".
            "1. Lend a book.\n".
            "2. Return a book.\n".
            "3. Register a book.\n".
            "4. View all books.\n".
            "5. View all loans.\n".
            "6. terminate.\n"
        );
        if($method == "6") { break; }

        switch($method) {

            case "1" : // 図書貸出機能の呼び出し

                $bookNum = readline("Enter the number of a book.\n");
                // 貸出対象の図書番号を入力

                $memberName = readline("Enter your name.\n");
                // 利用者名を入力

                $foundBook = $bookManager->findBook($bookNum);
                // 入力された番号に対応する Book オブジェクトを取得

                if($foundBook === null) { // 検索失敗時

                    echo "No results. \n";
                    break;
                    
                } else $book = $foundBook; // 検索成功時、Book オブジェクトを代入
 
                $member = new Member($memberName);
                // 入力された名前で Member オブジェクトを生成

                $lendManager->lendTo($book, $member); 
                // Book, Member オブジェクトを使って単一の貸出記録を作成
                break;

            case "2" : // 図書返却機能の呼び出し

                $bookNum = readline("Enter the number of a book. \n");
                // 返却対象の図書番号を入力

                $foundLoan = $lendManager->findLoanByBookNum($bookNum);
                // 入力された番号を持つ貸出記録を検索

                if($foundLoan === null) { // 検索失敗時
                 
                    echo "No results. \n";
                    break;

                } else {
                    
                    $gotBook = $foundLoan->getBook();
                    
                    if($lendManager->returnFrom($gotBook)){
                    // 検索成功時、該当の貸出記録に紐づく Book オブジェクトを利用して、
                    // 全体の貸出記録を走査し、
                    // この Book オブジェクトを保持している単一の貸出記録を全体の記録から削除する。
                        echo "Return completed. \n";

                    }else echo "No results. \n"; // 返却可能な図書が見つからなかった場合
                    
                    break;
                }

            case "3" : // 図書登録機能の呼び出し

                $bookNum = readline("Enter the number of a book. \n");
                // 登録する図書番号を入力

                $bookName = readline("Enter the name of a book. \n");
                // 登録する図書名を入力

                $book = new Book($bookNum, $bookName);
                // 入力された番号と名前で Book オブジェクトを生成

                $bookManager->register($book);
                // 生成したオブジェクトを図書リストに登録
                echo "Registration successful. \n";
                break;

            case "4" : // 登録済み図書一覧表示
                $bookManager->view();
                break;

            case "5" : // 貸出記録一覧表示
                $lendManager->view();
                break;

            default : echo "Invalid method selected. \n";
        }
    }
?>