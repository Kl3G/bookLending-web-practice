<?php

// index.php
date_default_timezone_set('Asia/Tokyo');

$root = __DIR__;
require_once($root . '/Entities/Book.php');
require_once($root . '/Entities/Loan.php');

require_once($root . '/UseCases/LendBook/LendBookUsecase.php');
require_once($root . '/UseCases/LendBook/LendBookRequest.php');
require_once($root . '/UseCases/LendBook/Ports/LoanGateway.php');
require_once($root . '/Adapters/Gateways/InmemoryLoanGateway.php');

require_once($root . '/UseCases/ReturnBook/ReturnBookUsecase.php');

require_once($root . '/UseCases/RegisterBook/RegisterBookUsecase.php');
require_once($root . '/UseCases/RegisterBook/RegisterBookRequest.php');
require_once($root . '/UseCases/RegisterBook/Ports/BookGateway.php');
require_once($root . '/Adapters/Gateways/InmemoryBookGateway.php');

require_once($root . '/UseCases/ViewBooksAndLoan/ViewBooksUsecase.php');
require_once($root . '/Adapters/Presenters/BookPresenter.php');
require_once($root . '/UseCases/ViewBooksAndLoan/ViewLoansUsecase.php');
require_once($root . '/Adapters/Presenters/LoanPresenter.php');

require_once($root . '/UseCases/DeleteBook/DeleteBookUsecase.php');

$lendBookUsecase = new LendBookUsecase();
$inmemoryLoanGateway = new InmemoryLoanGateway();

$returnBookUsecase = new ReturnBookUsecase();

$registerBookUsecase = new RegisterBookUsecase();
$inmemoryBookGateway = new InmemoryBookGateway();

$viewBooksUsecase = new ViewBooksUsecase();
$bookPresenter = new BookPresenter();
$viewLoansUsecase = new ViewLoansUsecase();
$loanPresenter = new LoanPresenter();

$deleteBookUsecase = new DeleteBookUsecase();

while (true) {

    // メニュー表示およびユーザー入力取得
    $method = readline(
        "\nSelect a number:\n".
        "1. Lend a book.\n".
        "2. Return a book.\n".
        "3. Register a book.\n".
        "4. View all books.\n".
        "5. View all loans.\n".
        "6. Delete a book.\n".
        "0. terminate.\n"
    );
    if ($method == "0") break;

    switch ($method) {

        case "1": // 図書貸出機能の呼び出し
            $bookNumber = readline("\nEnter the number of a book.\n"); // 貸出対象の図書番号を入力
            $memberName = readline("Enter your name.\n"); // 利用者名を入力
            $today = date('Y-m-d'); // 現在の日付
            $lendBookRequest = new LendBookRequest($bookNumber, $memberName, $today); // DTO生成
            try {

                $lendBookUsecase->lendBook($lendBookRequest, $inmemoryBookGateway, $inmemoryLoanGateway); // Usecase呼出
                echo "\nThe loan has been completed.\n";
                break;
            } catch (Throwable $e) {

                echo $e->getMessage();
                break;
            }

        case "2": // 図書返却機能の呼び出し
            $bookNumber = readline("\nEnter the number of a book.\n"); // 返却対象の図書番号を入力
            try {

                $returnBookUsecase->returnBook($bookNumber, $inmemoryBookGateway, $inmemoryLoanGateway);
                echo "\nReturn completed.\n";
                break;
            } catch (Throwable $e) {

                echo $e->getMessage();
                break;
            }

        case "3": // 図書登録機能の呼び出し
            $bookNumber = readline("\nEnter the number of a book.\n"); // 登録する図書番号を入力
            $bookName = readline("\nEnter the name of a book.\n"); // 登録する図書名を入力
            $registerBookRequest = new RegisterBookRequest($bookNumber, $bookName);

            try {

                $registerBookUsecase->registerBook($registerBookRequest, $inmemoryBookGateway);
                echo "\nRegistration successful.\n";
                break;
            } catch (Throwable $e) {

                echo $e->getMessage();
                break;
            }

        case "4": // 登録済み図書一覧表示
            echo "\nThe Book List.\n";
            print_r($viewBooksUsecase->viewBooks($inmemoryBookGateway, $bookPresenter));
            break;

        case "5": // 貸出記録一覧表示
            echo "\nThe Loan List.\n";
            print_r($viewLoansUsecase->viewLoans($inmemoryLoanGateway, $loanPresenter));
            break;

        case "6": // 図書削除機能の呼び出し
            $bookNumber = readline("\nEnter the number of a book.\n"); // 削除する書籍番号を入力
            try {

                $deleteBookUsecase->deleteBook($bookNumber, $inmemoryBookGateway);
                echo "\n The book with number $bookNumber has been deleted. \n";
                break;
            } catch(Throwable $e) {

                echo $e->getMessage();
                break;
            }

        default:
            echo "\n Invalid method selected. \n";
    }
}
