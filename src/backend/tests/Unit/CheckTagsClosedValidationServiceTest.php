<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\TagClosedValidationService;

class CheckTagsClosedValidationServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    protected $tag_closed_service;
    protected function setUp(): void
    {
        $this->tag_closed_service = new TagClosedValidationService();
        parent::setUp();
    }
    public function testTagClosedValidationServiceWorksCorrectlyWithAllowedTags(): void
    {
        $validTestContentExample1 = "<a href='new.wordcard.tech'> dasdasdasaaa dasdasdas </a> hfsdkfl <i> hjhh </i>";    
        $validTestContentExample2 = "<code> dasdasdasaaa dasdasdas </code> hfsdkfl <i> hjhh </i>";
        $validTestContentExample3 = "<strong> dasdasdasaaa dasdasdas </strong> hfsdkfl <i> hjhh </i>";

        $this->assertTrue($this->tag_closed_service->checkString($validTestContentExample1));
        $this->assertTrue($this->tag_closed_service->checkString($validTestContentExample2));
        $this->assertTrue($this->tag_closed_service->checkString($validTestContentExample3));
    }

    public function testTagClosedValidationServiceWorksCorrectlyWithExtendedTags(): void
    {
        $validExampleWithLink = "<a href='dsasdasdasdasd'> ddd </a>"; 
        $styledTagsCheck = "<code> console.log('new.wordcard.tech is nice') </code>";

        $this->assertTrue($this->tag_closed_service->checkString($validExampleWithLink));
        $this->assertTrue($this->tag_closed_service->checkString($styledTagsCheck));
    }

    public function testTagClosedValidationServiceWorksCorrectlyWithForbiddenTags(): void
    {
        $notAlowedTagsExample ="<h1> ddd </h1>";
        $xssAtack ="<xss onafterscriptexecute=alert(1)><script>1</script>";
        $cssExample= '<style>@keyframes x{from {left:0;}to {left: 1000px;}}:target {animation:10s ease-in-out 0s 1 x;}</style><xss id=x style="position:absolute;" onanimationcancel="print()"></xss>';

        $this->assertFalse($this->tag_closed_service->checkString($notAlowedTagsExample));
        $this->assertFalse($this->tag_closed_service->checkString($xssAtack));
        $this->assertFalse($this->tag_closed_service->checkString($cssExample));
    }

    public function testTagClosedValidationServiceWorksCorrectlyWithNotClosedTags(): void
    {
        $notClosedAllowedTagsExample1 ="i> ddd </>";
        $notClosedAllowedTagsExample2 ="<code ddd </code>";
        $notClosedAllowedTagsExample3 ="<strong ddd </strong>";
        $notClosedAllowedTagsExample4 ="strong> ddd </strong>";

        $this->assertFalse($this->tag_closed_service->checkString($notClosedAllowedTagsExample1));
        $this->assertFalse($this->tag_closed_service->checkString($notClosedAllowedTagsExample2));
        $this->assertFalse($this->tag_closed_service->checkString($notClosedAllowedTagsExample3));
        $this->assertFalse($this->tag_closed_service->checkString($notClosedAllowedTagsExample4));
    }


}
