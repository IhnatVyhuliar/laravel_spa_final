<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\TagClosedCheckService;

class CheckTagsClosedValidationServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    protected $tag_closed_service;
    protected function setUp(): void
    {
        $this->tag_closed_service = new TagClosedCheckService();
        parent::setUp();
    }
    public function testTagCloseCheckServiceWorksCorrectlyWithTags(): void
    {
        $string_example1 = "<a> dasdasdasaaa dasdasdas </a> hfsdkfl <strong> hjhh </strong";
        $not_valid_example ="<hh> ddd </hh>";
        $this->assertTrue($this->tag_closed_service->checkString($string_example1));
        //echo $not_valid_example;
        //$this->assertTrue(TagClosedCheckService:checkString($not_valid_example));
    }

    public function testTagCloseCheckServiceWorksCorrectlyWithNotValidTags(): void
    {

        $not_valid_example ="<a> ddd </a>";
        $this->assertTrue($this->tag_closed_service->checkString($not_valid_example));
        //echo $not_valid_example;
        //$this->assertTrue(TagClosedCheckService:checkString($not_valid_example));
    }

}
