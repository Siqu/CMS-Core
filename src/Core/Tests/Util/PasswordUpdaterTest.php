<?php

namespace Siqu\CMS\Core\Tests\Util;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Util\PasswordUpdater;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Class PasswordUpdaterTest
 * @package Siqu\CMS\Core\Tests\Util
 */
class PasswordUpdaterTest extends TestCase
{
    /** @var PasswordEncoderInterface|MockObject */
    private $encoder;
    /** @var EncoderFactory|MockObject */
    private $encoderFactory;
    /** @var PasswordUpdater */
    private $updater;

    /**
     * Should create instance
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            PasswordUpdater::class,
            $this->updater
        );
    }

    /**
     * Should hash password via encoder.
     */
    public function testHashPassword(): void
    {
        $plainPassword = 'test.1234';
        $encodedPassword = 'encoded';

        $user = new CMSUser();
        $user->setPlainPassword($plainPassword);

        $this->encoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with(
                $plainPassword,
                $user->getSalt()
            )
            ->willReturn($encodedPassword);

        $this->updater->hashPassword($user);

        $this->assertNull($user->getPlainPassword());
        $this->assertEquals(
            $encodedPassword,
            $user->getPassword()
        );
    }

    /**
     * Should not change password
     */
    public function testHashPasswordEmpty(): void
    {
        $user = new CMSUser();

        $this->encoder
            ->expects($this->never())
            ->method('encodePassword');

        $this->updater->hashPassword($user);
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->encoderFactory = $this->getMockBuilder(EncoderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->encoder = $this->getMockBuilder(PasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->encoderFactory
            ->method('getEncoder')
            ->willReturn($this->encoder);

        $this->updater = new PasswordUpdater($this->encoderFactory);
    }
}
