<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use App\Tests\Traits\Entity\DataProviderTrait;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{

    use DataProviderTrait;

    public function testItWorks(): void
    {
        self::assertEquals(42, 42);
    }

    public function testItWorksSame() : void
    {
        self::assertSame(42, 42);
    }

    public function testCanGetAnsSetData(): void
    {
        $dino = (new Dinosaur())
            ->setName('Big Eaty')
            ->setGenus('Tyrannosaurus')
            ->setLength(15)
            ->setenclosure('Paddock A');

        self::assertGreaterThan(
            10,
            $dino->getLength(),
            message: 'Dino is supposed to be greater than 10 meters'
        );

        self::assertSame('Big Eaty', $dino->getName());
        self::assertSame('Tyrannosaurus', $dino->getGenus());
        self::assertSame(15, $dino->getLength());
        self::assertSame('Paddock A', $dino->getEnclosure());
    }

    /**
     * @param int $length
     * @param string $expectedSize
     * @dataProvider sizeDescriptionProvider
     * @return void
     */
    public function testDino10MetersOrGreaterIsLarge(int $length, string $expectedSize): void
    {
        $dino = (new Dinosaur())
            ->setName('Big Eaty')
            ->setLength($length);

        self::assertSame($expectedSize, $dino->getSizeDescription(), 'This is supposed to be a large Dinosaur');
    }

    public function testDinoBetween5And9MetersIsMedium(): void
    {
        $dino = (new Dinosaur())
            ->setName('Big Eaty')
            ->setLength(5);

        self::assertSame('Medium', $dino->getSizeDescription(), 'This is supposed to be a medium Dinosaur');
    }
    public function testDinoUnder5MetersIsSmall(): void
    {
        $dino = (new Dinosaur())
            ->setName('Big Eaty')
            ->setLength(4);
        self::assertSame('Small', $dino->getSizeDescription(), 'This is supposed to be a small Dinosaur');
    }

    public function testIsAcceptingVisitorsByDefault(): void
    {
        $dino = new Dinosaur();
        $dino->setName('Dennis');

        self::assertTrue($dino->isAcceptingVisitors());
    }

    public function testIsNotAcceptingVisitorsIfSick(): void
    {
        $dino = new Dinosaur();

        $dino->setName('Dennis');
        $dino->setHealth(HealthStatus::SICK);

        self::assertFalse($dino->isAcceptingVisitors());
    }


    /**
     * @param HealthStatus $healthStatus
     * @param bool $expectedVisitorsStatus
     * @dataProvider healthStatusProvider
     * @return void
     */
    public function testIsAcceptingVisitorsBasedOnHealthStatus(HealthStatus $healthStatus, bool $expectedVisitorsStatus): void
    {
        $dino = new Dinosaur();

        $dino->setName('Dennis');
        $dino->setHealth($healthStatus);

        self::assertSame($expectedVisitorsStatus, $dino->isAcceptingVisitors());
    }


}