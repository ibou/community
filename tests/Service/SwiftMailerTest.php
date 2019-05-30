<?php

namespace App\Tests\Service;

use App\Service\Mailer\Adapter\SwiftMailer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SwiftMailerTest extends KernelTestCase
{
	/**
	 * @var SwiftMailer
	 */
	private $mailer;

	/**
	 * @var SwiftMailer
	 */
	private $smailer;

	/**
	 * @var \Twig\Environment;
	 */
	private $twig;

	private $content_twig;

	public function setUp()
	{
		$this->mailer = $this->createMock(\Swift_Mailer::class);
		$this->smailer = new SwiftMailer($this->mailer);
		$this->twig = $this->createMock(\Twig\Environment::class);

		$this->content_twig = <<<EOD
{% block subject %}
    A new order  {{reference}} has been created
{% endblock %}
{% block body %}
    A new order with reference {{reference}} was created.
    <b> un test html</b> Yes !!!!!!!
{% endblock %}
EOD;
	}

	public function testSend()
	{
		$obj = new \stdClass();
		$obj->email = "idiallo@gmail.com";
		$obj->to = ["to.destinataire@gmail.com"];
		$obj->name = "Ibahima D";
		$obj->subject = 'John Doe Did a thing on Johns website';
		$obj->body = 'Body of the email';
		$obj->data = [
			'subject' => $obj->subject,
			'body' => $obj->body,
		];

		$template = 'email/references.html.twig';
		$this->assertTrue(true);

		$this->mailer->expects($this->once())
			->method('send')
			->with($this->callback(function (\Swift_Message $actual_message) {
				$this->assertEquals('John Doe Did a thing on Johns website', $actual_message->getSubject());
				return true;
			}));
		$this->smailer->send($obj->email, $obj->to, $obj->subject, $obj->body);

		$this->assertSubject();

		$this->twig->load($this->content_twig);

		//$subject = $this->twig->render('subject', $this->content_twig);

		var_dump($this->twig);
	}

	public function assertSubject()
	{
		$this->twig->expects($this->once())->method('load')
			->willReturnSelf();
	}
}
