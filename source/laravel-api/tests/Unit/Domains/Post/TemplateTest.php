<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Post;

use App\Domains\Post\Slides\Template;
use Tests\TestCase;

class TemplateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_slide_template(): void
    {
        // Arrange
        $ppt = 'http://test.com/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';

        // Act
        $template = new Template($ppt, $google, $canva);

        // When
        $this->assertEquals($template->getPpt(), $ppt);
        $this->assertEquals($template->getGoogleSlide(), $google);
        $this->assertEquals($template->getCanva(), $canva);
        $this->assertInstanceOf(Template::class, $template);
    }

    public function test_slide_template_validation(): void
    {
        // Arrange
        $ppt = '//test.com/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';

        // When
        $this->expectExceptionMessage('Invalid Powerpoint URL');
        $this->expectException(\InvalidArgumentException::class);

        // Act
        new Template($ppt, $google, $canva);
    }

    public function test_slide_template_google_validation(): void
    {
        // Arrange
        $ppt = 'http://test.com/ppt';
        $google = 'https://test.com/google';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';

        // When
        $this->expectExceptionMessage('Invalid Google Slide URL');
        $this->expectException(\InvalidArgumentException::class);

        // Act
        new Template($ppt, $google, $canva);
    }

    public function test_slide_template_canva_validation(): void
    {
        // Arrange
        $ppt = 'http://test.com/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'http://test.com/canva';

        // When
        $this->expectExceptionMessage('Invalid Canva URL');
        $this->expectException(\InvalidArgumentException::class);

        // Act
        new Template($ppt, $google, $canva);
    }

    public function test_slide_template_equals(): void
    {
        // Arrange
        $ppt = 'http://test.com/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';
        $template = new Template($ppt, $google, $canva);
        $template2 = new Template($ppt, $google, $canva);

        // Act
        $isEquals = $template->equals($template2);

        // When
        $this->assertTrue($isEquals);
    }

    public function test_slide_template_equals_fail(): void
    {
        // Arrange
        $ppt = 'http://test.com/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';
        $template = new Template($ppt, $google, $canva);
        $template2 = new Template('http://test.com/pptFail', $google, $canva);


        // Act
        $isEquals = $template->equals($template2);

        // When
        $this->assertFalse($isEquals);
    }

    public function test_slide_template_to_json(): void
    {
        // Arrange
        $ppt = 'http://test.com/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';
        $template = new Template($ppt, $google, $canva);
        $expectedJson = '{"meta_key":"slide_template","meta_value":{"pptUrl":"http://test.com/ppt","googleSlideUrl":"https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy","canvaUrl":"https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview"}}';


        // Act
        $jsonTemplate = $template->toJson();

        // When
        $this->assertJson($jsonTemplate);
        $this->assertJsonStringEqualsJsonString($expectedJson, $jsonTemplate);
    }


    public function test_slide_template_from_json(): void
    {
        $templateJson = '{"meta_key":"slide_template","meta_value":{"pptUrl":"http://test.com/ppt","googleSlideUrl":"https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy","canvaUrl":"https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview"}}';

        // Act
        $template = Template::fromJson($templateJson);

        // When
        $this->assertInstanceOf(Template::class, $template);
        $this->assertEquals("http://test.com/ppt", $template->getPpt());
        $this->assertEquals("https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy", $template->getGoogleSlide());
        $this->assertEquals("https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview", $template->getCanva());
    }
}
