<?php

declare(strict_types=1);


namespace PWP\Model;
use PWP\Model\Book;

interface BookRepository
{
 
    public function getWorkByISBN(string $ISBN): Book ;

}