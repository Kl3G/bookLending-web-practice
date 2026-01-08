<?php

    // index.php
    require_once('./01_book.php');
    require_once('./02_member.php');
    require_once('./04_lendManager.php');
    require_once('./05_bookManager.php');
    require_once('./06_inputValidator.php');
    require_once('07_jsonStore.php');

    $lendManager = new LendManager();
    $bookManager = new BookManager();
    $loanInputValidator = new LoanInputValidator(); // 生成責任は Factory に分離可能
    $bookInputValidator = new BookInputValidator(); // 生成責任は Factory に分離可能
    $baseDir = __DIR__;
    $dataPath = $baseDir . DIRECTORY_SEPARATOR . "data.json";
    $jsonStore = new JsonStore($dataPath);
    date_default_timezone_set('Asia/Tokyo'); // 日本時間に設定
    $loanDate = date('Y-m-d'); // 日付形式を設定

    while(true) {

        // メニュー表示およびユーザー入力取得
        $method = readline(
            "\n Select a number: \n".
            "1. Lend a book.\n".
            "2. Return a book.\n".
            "3. Register a book.\n".
            "4. View all books.\n".
            "5. View all loans.\n".
            "6. Save the data.\n".
            "7. Load the data.\n".
            "8. Delete a book.\n".
            "0. terminate.\n"
        );
        if($method == "0") { break; }

        switch($method) {

            case "1" : // 図書貸出機能の呼び出し

                $bookNum = readline("\n Enter the number of a book.\n");
                // 貸出対象の図書番号を入力

                if($lendManager->isBookLent($bookNum)) {
                // 重複貸出の検証を実行
                    
                    echo "\n This book is already on loan.";
                    break;
                }

                $memberName = readline("Enter your name.\n");
                // 利用者名を入力

                $foundBook = $bookManager->findBook($bookNum);
                // 入力された番号に対応する Book オブジェクトを取得

                if(!$loanInputValidator->validate($bookNum, $memberName)) {
                    // validate という interface の method にのみ依存

                    echo "\nInvalid input. Enter a 3-digit book number and a member name (English or Japanese letters only).\n";
                    break;
                }

                if($foundBook === null) { // 検索失敗時

                    echo "\n No results. \n";
                    break;
                    
                } else {
                    
                    $book = $foundBook;
                    echo "\n The loan has been completed. \n";
                }// 検索成功時、Book オブジェクトを代入
 
                $member = new Member($memberName);
                // 入力された名前で Member オブジェクトを生成

                $lendManager->lendTo($book, $member, $loanDate); 
                // Book, Member オブジェクトを使って単一の貸出記録を作成
                break;

            case "2" : // 図書返却機能の呼び出し

                $bookNum = readline("\n Enter the number of a book. \n");
                // 返却対象の図書番号を入力

                $foundLoan = $lendManager->findLoanByBookNum($bookNum);
                // 入力された番号を持つ貸出記録を検索

                if($foundLoan === null) { // 検索失敗時
                 
                    echo "\n No results. \n";
                    break;

                } else {
                    
                    $gotBook = $foundLoan->getBook();
                    
                    if($lendManager->returnFrom($gotBook)){
                    // 検索成功時、該当の貸出記録に紐づく Book オブジェクトを利用して、
                    // 全体の貸出記録を走査し、
                    // この Book オブジェクトを保持している単一の貸出記録を全体の記録から削除する。
                        echo "\n Return completed. \n";

                    }else echo "\n No results. \n"; // 返却可能な図書が見つからなかった場合

                    break;
                }

            case "3" : // 図書登録機能の呼び出し

                $bookNum = readline("\n Enter the number of a book. \n");
                // 登録する図書番号を入力
                if($bookManager->isNumRegistered($bookNum)) {
                // 図書番号の重複検証を実行

                    echo "\n This book is already registered. \n";
                    break;
                }

                $bookName = readline("\n Enter the name of a book. \n");
                // 登録する図書名を入力

                if(!$bookInputValidator->validate($bookNum, $bookName)) {
                    // validate という interface の method にのみ依存

                    echo "\nInvalid input. Enter a 3-digit book number and a book name (English or Japanese letters only).\n";
                    break;
                }

                $book = new Book($bookNum, $bookName);
                // 入力された番号と名前で Book オブジェクトを生成

                $bookManager->register($book);
                // 生成したオブジェクトを図書リストに登録
                echo "\n Registration successful. \n";
                break;

            case "4" : // 登録済み図書一覧表示
                $bookManager->view();
                break;

            case "5" : // 貸出記録一覧表示
                $lendManager->view();
                break;

            case "6" : // データ保存機能の呼び出し
                $jsonStore->save($bookManager, $lendManager);
                echo "\n Data saved. \n";
                break;

            case "7" : // データ読込機能の呼び出し
                $parsedData = $jsonStore->load();
                if($parsedData === null) {

                    echo "\n No data to load. \n";

                }else { // load methodの戻り値を mapping methodに渡す

                    $jsonStore->mapping($parsedData, $bookManager, $lendManager);
                    echo "\n Data Loaded. \n";
                }
                break;

            case "8" :
                $bookNum = readline("\n Enter the number of a book. \n"); // 削除する書籍番号を入力
                
                if($lendManager->isBookLent($bookNum)) {
                // 書籍が貸出中の場合は switch 文を終了
                
                    echo "\n This book is on loan. \n";
                    break;
                }

                if($bookManager->deleteBook($bookNum) === false) { // 削除対象の書籍が存在しない場合

                    echo "\n No book to delete. \n";

                } else { // 書籍が存在し、削除に成功した場合

                    echo "\n The book with number $bookNum has been deleted. \n";
                }
                break;

            default : echo "\n Invalid method selected. \n";
        }
    }
?>