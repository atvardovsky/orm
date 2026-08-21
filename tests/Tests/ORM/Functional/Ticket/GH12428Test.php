<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Attributes\Group;

use function assert;

#[Group('GH-12428')]
final class GH12428Test extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchemaForModels(
            GH12428SingleTableBusinessCase::class,
            GH12428SingleTableBusinessCaseA::class,
            GH12428SingleTableContract::class,
            GH12428SingleTableAttachment::class,
            GH12428SingleTableEagerAttachment::class,
            GH12428JoinedBusinessCase::class,
            GH12428JoinedBusinessCaseA::class,
            GH12428JoinedContract::class,
            GH12428JoinedAttachment::class,
            GH12428CompositeBusinessCase::class,
            GH12428CompositeBusinessCaseA::class,
            GH12428CompositeContract::class,
            GH12428CompositeAttachment::class,
            GH12428PlainBusinessCase::class,
            GH12428PlainContract::class,
            GH12428PlainAttachment::class,
            GH12428NullableAttachment::class,
        );
    }

    public function testFindLoadsManyToOneTargetWithSingleTableInheritedAssociationIdentifier(): void
    {
        $ids = $this->createSingleTableFixture();

        $loadedAttachment = $this->_em->find(GH12428SingleTableAttachment::class, $ids['attachmentId']);
        assert($loadedAttachment instanceof GH12428SingleTableAttachment);

        self::assertInstanceOf(GH12428SingleTableContract::class, $loadedAttachment->contract);
    }

    public function testDqlLoadsManyToOneTargetWithSingleTableInheritedAssociationIdentifier(): void
    {
        $ids = $this->createSingleTableFixture();

        $loadedAttachment = $this->_em->createQueryBuilder()
            ->select('attachment')
            ->from(GH12428SingleTableAttachment::class, 'attachment')
            ->where('attachment.id = :id')
            ->setParameter('id', $ids['attachmentId'])
            ->getQuery()
            ->getSingleResult();
        assert($loadedAttachment instanceof GH12428SingleTableAttachment);

        self::assertInstanceOf(GH12428SingleTableContract::class, $loadedAttachment->contract);
    }

    public function testFindEagerLoadsManyToOneTargetWithSingleTableInheritedAssociationIdentifier(): void
    {
        $ids = $this->createSingleTableEagerFixture();

        $loadedAttachment = $this->_em->find(GH12428SingleTableEagerAttachment::class, $ids['attachmentId']);
        assert($loadedAttachment instanceof GH12428SingleTableEagerAttachment);

        self::assertInstanceOf(GH12428SingleTableContract::class, $loadedAttachment->contract);
        self::assertFalse($this->isUninitializedObject($loadedAttachment->contract));
    }

    public function testFindReusesTargetLoadedDuringAssociationIdentifierNormalization(): void
    {
        $ids = $this->createSingleTableFixture();

        $loadedAttachment = $this->_em->find(GH12428SingleTableAttachment::class, $ids['attachmentId']);
        assert($loadedAttachment instanceof GH12428SingleTableAttachment);

        $managedContract = $this->_em->getUnitOfWork()->tryGetById(
            ['id' => $ids['businessCaseId']],
            GH12428SingleTableContract::class,
        );

        self::assertSame($loadedAttachment->contract, $managedContract);
    }

    public function testFindLoadsManyToOneTargetWithJoinedInheritedAssociationIdentifier(): void
    {
        $ids = $this->createJoinedFixture();

        $loadedAttachment = $this->_em->find(GH12428JoinedAttachment::class, $ids['attachmentId']);
        assert($loadedAttachment instanceof GH12428JoinedAttachment);

        self::assertInstanceOf(GH12428JoinedContract::class, $loadedAttachment->contract);
    }

    public function testFindLoadsCompositeTargetWithInheritedAssociationIdentifier(): void
    {
        $ids = $this->createCompositeFixture();

        $loadedAttachment = $this->_em->find(GH12428CompositeAttachment::class, $ids['attachmentId']);
        assert($loadedAttachment instanceof GH12428CompositeAttachment);

        self::assertInstanceOf(GH12428CompositeContract::class, $loadedAttachment->contract);
        self::assertSame($ids['number'], $loadedAttachment->contract->number);

        $managedContract = $this->_em->getUnitOfWork()->tryGetById(
            ['businessCase' => $ids['businessCaseId'], 'number' => $ids['number']],
            GH12428CompositeContract::class,
        );

        self::assertSame($loadedAttachment->contract, $managedContract);
    }

    public function testNonInheritedAssociationIdentifierStillUsesLazyReference(): void
    {
        $businessCase = new GH12428PlainBusinessCase();
        $contract     = new GH12428PlainContract($businessCase);
        $attachment   = new GH12428PlainAttachment($contract);

        $this->_em->persist($businessCase);
        $this->_em->persist($contract);
        $this->_em->persist($attachment);
        $this->_em->flush();

        self::assertNotNull($attachment->id);

        $attachmentId = $attachment->id;
        $this->_em->clear();

        $loadedAttachment = $this->_em->find(GH12428PlainAttachment::class, $attachmentId);
        assert($loadedAttachment instanceof GH12428PlainAttachment);

        self::assertInstanceOf(GH12428PlainContract::class, $loadedAttachment->contract);
        self::assertTrue($this->isUninitializedObject($loadedAttachment->contract));
    }

    public function testNullManyToOneForeignKeyToAssociationIdentifierRemainsNull(): void
    {
        $attachment = new GH12428NullableAttachment();

        $this->_em->persist($attachment);
        $this->_em->flush();

        self::assertNotNull($attachment->id);

        $attachmentId = $attachment->id;
        $this->_em->clear();

        $loadedAttachment = $this->_em->find(GH12428NullableAttachment::class, $attachmentId);
        assert($loadedAttachment instanceof GH12428NullableAttachment);

        self::assertNull($loadedAttachment->contract);
    }

    /** @return array{attachmentId: int, businessCaseId: int} */
    private function createSingleTableFixture(): array
    {
        $businessCase = new GH12428SingleTableBusinessCaseA();
        $attachment   = new GH12428SingleTableAttachment($businessCase->contract);

        $this->_em->persist($businessCase);
        $this->_em->persist($attachment);
        $this->_em->flush();

        self::assertNotNull($attachment->id);
        self::assertNotNull($businessCase->id);

        $ids = [
            'attachmentId' => $attachment->id,
            'businessCaseId' => $businessCase->id,
        ];
        $this->_em->clear();

        return $ids;
    }

    /** @return array{attachmentId: int} */
    private function createSingleTableEagerFixture(): array
    {
        $businessCase = new GH12428SingleTableBusinessCaseA();
        $attachment   = new GH12428SingleTableEagerAttachment($businessCase->contract);

        $this->_em->persist($businessCase);
        $this->_em->persist($attachment);
        $this->_em->flush();

        self::assertNotNull($attachment->id);

        $ids = ['attachmentId' => $attachment->id];
        $this->_em->clear();

        return $ids;
    }

    /** @return array{attachmentId: int} */
    private function createJoinedFixture(): array
    {
        $businessCase = new GH12428JoinedBusinessCaseA();
        $attachment   = new GH12428JoinedAttachment($businessCase->contract);

        $this->_em->persist($businessCase);
        $this->_em->persist($attachment);
        $this->_em->flush();

        self::assertNotNull($attachment->id);

        $ids = ['attachmentId' => $attachment->id];
        $this->_em->clear();

        return $ids;
    }

    /** @return array{attachmentId: int, businessCaseId: int, number: string} */
    private function createCompositeFixture(): array
    {
        $businessCase = new GH12428CompositeBusinessCaseA();
        $attachment   = new GH12428CompositeAttachment($businessCase->contract);

        $this->_em->persist($businessCase);
        $this->_em->persist($attachment);
        $this->_em->flush();

        self::assertNotNull($attachment->id);
        self::assertNotNull($businessCase->id);

        $ids = [
            'attachmentId' => $attachment->id,
            'businessCaseId' => $businessCase->id,
            'number' => $businessCase->contract->number,
        ];
        $this->_em->clear();

        return $ids;
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_sti_business_case')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'dtype')]
#[ORM\DiscriminatorMap(['a' => GH12428SingleTableBusinessCaseA::class])]
abstract class GH12428SingleTableBusinessCase
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    #[ORM\OneToOne(mappedBy: 'id', cascade: ['persist'])]
    public GH12428SingleTableContract $contract;

    public function __construct()
    {
        $this->contract = new GH12428SingleTableContract($this);
    }
}

#[ORM\Entity]
class GH12428SingleTableBusinessCaseA extends GH12428SingleTableBusinessCase
{
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_sti_attachment')]
class GH12428SingleTableAttachment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    public function __construct(
        #[ORM\ManyToOne]
        public GH12428SingleTableContract $contract,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_sti_eager_attachment')]
class GH12428SingleTableEagerAttachment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    public function __construct(
        #[ORM\ManyToOne(fetch: 'EAGER')]
        public GH12428SingleTableContract $contract,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_sti_contract')]
class GH12428SingleTableContract
{
    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne(inversedBy: 'contract')]
        #[ORM\JoinColumn(name: 'id')]
        public GH12428SingleTableBusinessCase $id,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_joined_business_case')]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'dtype')]
#[ORM\DiscriminatorMap(['a' => GH12428JoinedBusinessCaseA::class])]
abstract class GH12428JoinedBusinessCase
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    #[ORM\OneToOne(mappedBy: 'id', cascade: ['persist'])]
    public GH12428JoinedContract $contract;

    public function __construct()
    {
        $this->contract = new GH12428JoinedContract($this);
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_joined_business_case_a')]
class GH12428JoinedBusinessCaseA extends GH12428JoinedBusinessCase
{
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_joined_attachment')]
class GH12428JoinedAttachment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    public function __construct(
        #[ORM\ManyToOne]
        public GH12428JoinedContract $contract,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_joined_contract')]
class GH12428JoinedContract
{
    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne(inversedBy: 'contract')]
        #[ORM\JoinColumn(name: 'id')]
        public GH12428JoinedBusinessCase $id,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_composite_business_case')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'dtype')]
#[ORM\DiscriminatorMap(['a' => GH12428CompositeBusinessCaseA::class])]
abstract class GH12428CompositeBusinessCase
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    #[ORM\OneToOne(mappedBy: 'businessCase', cascade: ['persist'])]
    public GH12428CompositeContract $contract;

    public function __construct()
    {
        $this->contract = new GH12428CompositeContract($this, 'main');
    }
}

#[ORM\Entity]
class GH12428CompositeBusinessCaseA extends GH12428CompositeBusinessCase
{
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_composite_attachment')]
class GH12428CompositeAttachment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(name: 'contract_number', referencedColumnName: 'contract_number')]
        #[ORM\JoinColumn(name: 'contract_business_case_id', referencedColumnName: 'business_case_id')]
        public GH12428CompositeContract $contract,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_composite_contract')]
class GH12428CompositeContract
{
    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne(inversedBy: 'contract')]
        #[ORM\JoinColumn(name: 'business_case_id', referencedColumnName: 'id')]
        public GH12428CompositeBusinessCase $businessCase,
        #[ORM\Id]
        #[ORM\Column(name: 'contract_number')]
        public string $number,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_plain_business_case')]
class GH12428PlainBusinessCase
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_plain_attachment')]
class GH12428PlainAttachment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    public function __construct(
        #[ORM\ManyToOne]
        public GH12428PlainContract $contract,
    ) {
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_nullable_attachment')]
class GH12428NullableAttachment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    #[ORM\ManyToOne(targetEntity: GH12428PlainContract::class)]
    #[ORM\JoinColumn(nullable: true)]
    public GH12428PlainContract|null $contract = null;
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12428_plain_contract')]
class GH12428PlainContract
{
    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne]
        #[ORM\JoinColumn(name: 'id')]
        public GH12428PlainBusinessCase $id,
    ) {
    }
}
