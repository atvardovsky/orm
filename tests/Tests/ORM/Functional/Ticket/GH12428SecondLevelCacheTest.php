<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Attributes\Group;

use function assert;

#[Group('GH-12428')]
final class GH12428SecondLevelCacheTest extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        $this->enableSecondLevelCache();

        parent::setUp();

        $this->createSchemaForModels(
            GH12428CachedBusinessCase::class,
            GH12428CachedBusinessCaseA::class,
            GH12428CachedContract::class,
            GH12428CachedAttachment::class,
        );
    }

    public function testLoadsTargetWithInheritedAssociationIdentifier(): void
    {
        $ids = $this->createCachedFixture();

        $cache = $this->_em->getCache();
        self::assertNotNull($cache);
        self::assertTrue($cache->containsEntity(GH12428CachedAttachment::class, $ids['attachmentId']));

        $loadedAttachment = $this->_em->find(GH12428CachedAttachment::class, $ids['attachmentId']);
        assert($loadedAttachment instanceof GH12428CachedAttachment);

        self::assertInstanceOf(GH12428CachedContract::class, $loadedAttachment->contract);
    }

    /** @return array{attachmentId: int} */
    private function createCachedFixture(): array
    {
        $businessCase = new GH12428CachedBusinessCaseA();
        $attachment   = new GH12428CachedAttachment($businessCase->contract);

        $this->_em->persist($businessCase);
        $this->_em->persist($attachment);
        $this->_em->flush();

        self::assertNotNull($attachment->id);

        $ids = ['attachmentId' => $attachment->id];
        $this->_em->clear();

        return $ids;
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_cache_business_case')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'dtype')]
#[ORM\DiscriminatorMap(['a' => GH12428CachedBusinessCaseA::class])]
abstract class GH12428CachedBusinessCase
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    #[ORM\OneToOne(mappedBy: 'id', cascade: ['persist'])]
    public GH12428CachedContract $contract;

    public function __construct()
    {
        $this->contract = new GH12428CachedContract($this);
    }
}

#[ORM\Entity]
class GH12428CachedBusinessCaseA extends GH12428CachedBusinessCase
{
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_cache_attachment')]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE')]
class GH12428CachedAttachment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    public function __construct(
        #[ORM\ManyToOne]
        public GH12428CachedContract $contract,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_cache_contract')]
class GH12428CachedContract
{
    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne(inversedBy: 'contract')]
        #[ORM\JoinColumn(name: 'id')]
        public GH12428CachedBusinessCase $id,
    ) {
    }
}
