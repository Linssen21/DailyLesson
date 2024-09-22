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
        $ppt = 'uploads/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';
        Template::CANVA_URL_REGEX;
        // Act
        $template = new Template($ppt, $google, $canva);

        // Assert
        $this->assertEquals($template->getPpt(), $ppt);
        $this->assertEquals($template->getGoogleSlide(), $google);
        $this->assertEquals($template->getCanva(), $canva);
        $this->assertInstanceOf(Template::class, $template);
    }

    public function test_slide_template_google_validation(): void
    {
        // Arrange
        $ppt = 'uploads/ppt';
        $google = 'https://test.com/google';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';

        // Assert
        $this->expectExceptionMessage('Invalid Google Slide URL');
        $this->expectException(\InvalidArgumentException::class);

        // Act
        new Template($ppt, $google, $canva);
    }

    public function test_slide_template_canva_validation(): void
    {
        // Arrange
        $ppt = 'uploads/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'http://test.com/canva';

        // Assert
        $this->expectExceptionMessage('Invalid Canva URL');
        $this->expectException(\InvalidArgumentException::class);

        // Act
        new Template($ppt, $google, $canva);
    }

    public function test_slide_template_equals(): void
    {
        // Arrange
        $ppt = 'uploads/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';
        $template = new Template($ppt, $google, $canva);
        $template2 = new Template($ppt, $google, $canva);

        // Act
        $isEquals = $template->equals($template2);

        // Assert
        $this->assertTrue($isEquals);
    }

    public function test_slide_template_equals_fail(): void
    {
        // Arrange
        $ppt = 'uploads/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';
        $template = new Template($ppt, $google, $canva);
        $template2 = new Template('http://test.com/pptFail', $google, $canva);

        // Act
        $isEquals = $template->equals($template2);

        // Assert
        $this->assertFalse($isEquals);
    }

    public function test_slide_template_to_json(): void
    {
        // Arrange
        $ppt = 'uploads/ppt';
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';
        $template = new Template($ppt, $google, $canva);
        $expectedJson = '{"meta_key":"slide_template","meta_value":{"pptPath":"uploads/ppt","googleSlideUrl":"https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy","canvaUrl":"https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview"}}';


        // Act
        $jsonTemplate = $template->toJson();

        // Assert
        $this->assertJson($jsonTemplate);
        $this->assertJsonStringEqualsJsonString($expectedJson, $jsonTemplate);
    }


    public function test_slide_template_from_json(): void
    {
        $templateJson = '{"meta_key":"slide_template","meta_value":{"pptPath":"uploads/ppt","googleSlideUrl":"https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy","canvaUrl":"https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview"}}';

        // Act
        $template = Template::fromJson($templateJson);

        // Assert
        $this->assertInstanceOf(Template::class, $template);
        $this->assertEquals("uploads/ppt", $template->getPpt());
        $this->assertEquals("https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy", $template->getGoogleSlide());
        $this->assertEquals("https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview", $template->getCanva());
    }

    public function test_slide_ppt_only(): void
    {
        // Arrange
        $ppt = 'uploads/ppt';

        // Act
        $template = new Template($ppt);

        // Assert
        $this->assertEquals($template->getPpt(), $ppt);
        $this->assertInstanceOf(Template::class, $template);
    }

    public function test_slide_google_only(): void
    {
        // Arrange
        $google = 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy';

        // Act
        $template = new Template(googleSlideUrl: $google);

        // Assert
        $this->assertEquals($template->getGoogleSlide(), $google);
        $this->assertInstanceOf(Template::class, $template);
    }


    public function test_slide_canva_only(): void
    {
        // Arrange
        $canva = 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview';

        // Act
        $template = new Template(canvaUrl: $canva);

        // Assert
        $this->assertEquals($template->getCanva(), $canva);
        $this->assertInstanceOf(Template::class, $template);
    }
}
