<?php

    class RegisterBookUsecase {

        public function registerBook(

            RegisterBookRequest $registerBookRequest,
            BookGateway $bookGateway,
        ): void {

            $bookNumber = $registerBookRequest->bookNumber;
            $bookName = $registerBookRequest->bookName;

            if($bookGateway->findByNumber($bookNumber)) {
                throw new Exception("This number is already registered.");
            }
            $bookGateway->register(new Book($bookNumber, $bookName));
        }
    }
    
?>